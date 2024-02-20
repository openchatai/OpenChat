from uuid import uuid4
import os
import requests
import json
from django.http import JsonResponse, HttpResponseRedirect, HttpResponseServerError
from django.shortcuts import render
from django.urls import reverse
from django.contrib import messages
from django.contrib.auth import authenticate, login, logout
from django.views.decorators.http import require_POST
from django.core.serializers.json import DjangoJSONEncoder
from django.core.cache import cache
from django.utils.safestring import mark_safe
from django.utils import timezone
from django.urls import reverse
from django.shortcuts import get_object_or_404, redirect
from web.models.chatbot import Chatbot
from web.models.chatbot_settings import ChatbotSetting
from web.models.chat_histories import ChatHistory
from web.models.codebase_data_sources import CodebaseDataSource
from web.signals.codebase_datasource_was_created import codebase_data_source_added
from web.signals.pdf_datasource_was_added import pdf_data_source_added
from web.services.handle_pdf_datasource import HandlePdfDataSource
from web.interfaces.dashboard import get_discussion_counts, get_data_source_counts
from web.signals.codebase_datasource_was_created import codebase_data_source_added
from web.signals.chatbot_was_created import chatbot_was_created
from web.enums.chatbot_initial_prompt_enum import ChatBotInitialPromptEnum
from web.enums.common_enums import ChatBotDefaults
from web.utils.common import get_session_id
from web.utils.common import generate_chatbot_name
from api.utils.get_prompts import get_qa_prompt_by_mode
from web.models.pdf_data_sources import PdfDataSource
from api.utils.init_vector_store import delete_from_vector_store


def check_authentication(view_func):
    def wrapper(request, *args, **kwargs):
        if not request.user.is_authenticated:
            return HttpResponseRedirect(reverse("login"))
        return view_func(request, *args, **kwargs)

    return wrapper


def login_view(request):
    if request.method == "POST":
        username = request.POST["username"]
        password = request.POST["password"]
        user = authenticate(request, username=username, password=password)
        if user is not None:
            if user.last_login is None:
                # Redirect to modify user page if the user has never logged in
                login(request, user)  # Log in the user to start the session
                return HttpResponseRedirect(reverse("modify_user"))
            login(request, user)
            return HttpResponseRedirect(reverse("index"))
        else:
            return render(
                request, "login.html", {"error": "Invalid username or password"}
            )
    else:
        return render(request, "login.html")


def logout_view(request):
    logout(request)
    return HttpResponseRedirect(reverse("login"))


@check_authentication
def index(request):
    chatbots = Chatbot.objects.filter(status=1)
    # Cache keys
    discussion_counts_cache_key = "discussion_counts"
    data_source_counts_cache_key = "data_source_counts"

    # Try to get cached data
    discussion_counts = cache.get(discussion_counts_cache_key)
    data_source_counts = cache.get(data_source_counts_cache_key)

    # If cache is empty, compute the data and set it in the cache
    if not request.user.is_superuser:
        chatbots = chatbots.filter(user=request.user)

    # If cache is empty, compute the data and set it in the cache
    if not discussion_counts:
        discussion_counts = get_discussion_counts()
        cache.set(
            discussion_counts_cache_key, discussion_counts, 3600
        )  # Cache for 1 hour

    if not data_source_counts:
        data_source_counts = get_data_source_counts()
        cache.set(
            data_source_counts_cache_key, data_source_counts, 3600
        )  # Cache for 1 hour

    # Pass the discussion counts to the template
    return render(
        request,
        "index.html",
        {
            "chatbots": chatbots,
            "discussion_counts_json": mark_safe(
                json.dumps(list(discussion_counts), cls=DjangoJSONEncoder)
            ),
            "data_sources_counts_json": mark_safe(
                json.dumps(list(data_source_counts), cls=DjangoJSONEncoder)
            ),
        },
    )


@check_authentication
@require_POST
def create_via_website_flow(request):
    name = request.POST.get("name") or ChatBotDefaults.NAME()
    website = request.POST.get("website")
    prompt_message = (
        request.POST.get("prompt_message")
        or ChatBotInitialPromptEnum.AI_ASSISTANT_INITIAL_PROMPT.value
    )

    chatbot = Chatbot.objects.create(
        user=request.user,
        id=uuid4(),
        name=name,
        token=str(uuid4())[:20],
        website=website,
        prompt_message=prompt_message,
        status=1,
    )

    # Trigger the ChatbotWasCreated event (if using Django signals or channels)
    chatbot_was_created.send(
        sender="create_via_codebase_flow",
        id=chatbot.id,
        name=chatbot.name,
        website=chatbot.website,
        prompt_message=chatbot.prompt_message,
        status=1,
    )

    return HttpResponseRedirect(reverse("onboarding.config", args=[str(chatbot.id)]))


@check_authentication
@require_POST
def create_via_pdf_flow(request):
    skip_pdf_upload = "skip_pdf_upload" in request.POST
    name = request.POST.get("name") or ChatBotDefaults.NAME()
    prompt_message = (
        request.POST.get("prompt_message")
        or ChatBotInitialPromptEnum.AI_ASSISTANT_INITIAL_PROMPT.value
    )

    chatbot = Chatbot.objects.create(
        user=request.user,
        id=uuid4(),
        name=name,
        token=str(uuid4())[:20],
        prompt_message=prompt_message,
        status=1,
    )

    if skip_pdf_upload == True:
        return HttpResponseRedirect(
            reverse("onboarding.config", args=[str(chatbot.id)])
        )
    # ... rest of the function remains unchanged ...
    delete_folder_flag = "delete_folder_flag" in request.POST
    files = request.FILES.getlist("pdffiles")
    # Handle the PDF data source
    handle_pdf = HandlePdfDataSource(chatbot, files)
    data_source = handle_pdf.handle()

    # Trigger the PdfDataSourceWasAdded event
    pdf_data_source_added.send(
        sender="create_via_pdf_flow",
        bot_id=chatbot.id,
        data_source_id=data_source.id,
        delete_folder_flag=delete_folder_flag,
    )
    return HttpResponseRedirect(reverse("onboarding.config", args=[str(chatbot.id)]))


@check_authentication
@require_POST
def update_character_settings(request, id):
    print("update_character_settings", str(id))

    chatbot_id = id  # request.POST.get('chatbot_id')
    character_name = request.POST.get("character_name")

    chatbot = Chatbot.objects.get(id=chatbot_id)
    # chatbot.create_or_update_setting('character_name', character_name)
    ChatbotSetting.objects.update_or_create(
        chatbot_id=chatbot.id, defaults={"name": character_name}
    )
    return HttpResponseRedirect(reverse("onboarding.done", args=[str(chatbot.id)]))


@require_POST
def send_message(request, token):
    # Find the chatbot by token
    bot = get_object_or_404(Chatbot, token=token)

    # Get the question and history from the request
    question = request.POST.get("question")
    session_id = get_session_id(request=request, bot_id=bot.id)
    history = ChatHistory.objects.filter(session_id=session_id)

    print(history)
    mode = request.POST.get("mode")
    initial_prompt = bot.prompt_message

    # Remove null and empty values and empty arrays or objects from the history
    history = [
        value
        for value in history
        if value is not None and value != "" and value != [] and value != {}
    ]

    # Call the API to send the message to the chatbot with a timeout of 5 seconds
    try:
        response = requests.post(
            "http://localhost:3000/api/chat",
            json={
                "question": question,
                "history": history,
                "namespace": str(bot.id),
                "mode": mode,
                "initial_prompt": initial_prompt,
            },
            timeout=5,
        )
        response.raise_for_status()
    except requests.RequestException as e:
        return HttpResponseServerError("Something went wrong")

    # Create a ChatbotResponse instance from the API response
    bot_response = response.json()

    session_id = get_session_id(request, bot.id)

    if session_id is not None:
        # Save chat history
        ChatHistory.objects.create(
            user=request.user,
            id=uuid4(),
            chatbot=bot,
            from_user=True,
            message=question,
            session_id=session_id,
        )
        ChatHistory.objects.create(
            user=request.user,
            id=uuid4(),
            chatbot=bot,
            from_user=False,
            message=bot_response["botReply"],
            session_id=session_id,
        )

    # Return the response from the chatbot
    return JsonResponse(
        {
            "botReply": bot_response["botReply"],
            "sources": bot_response["sources"],
        }
    )


def get_chat_view(request, token):
    # Find the chatbot by token
    bot = Chatbot.objects.filter(token=token).first()

    # If bot is None, redirect to APP_URL
    if bot is None:
        return redirect(os.getenv("APP_URL"))

    # Initiate a cookie if it doesn't exist
    cookie_name = "chatbot_" + str(bot.id)
    app_url = os.getenv("APP_URL")  # better to have full URL here for HTML template.
    if cookie_name not in request.COOKIES:
        cookie_value = str(uuid4())[:20]
        response = render(request, "chat.html", {"bot": bot})
        response.set_cookie(
            cookie_name, cookie_value, max_age=60 * 60 * 24 * 365
        )  # 1 year
        return response

    return render(
        request, "chat.html", {"bot": bot, "APP_URL": app_url, "title": str(bot.name)}
    )


@check_authentication
def create_via_codebase_flow(request):
    if request.method == "POST":
        prompt_message = request.POST.get("prompt_message") or get_qa_prompt_by_mode(
            mode="pair_programmer", initial_prompt=None
        )
        repo_url = request.POST.get("repo")

        # name = request.POST.get('name')
        name = request.POST.get("name") or ChatBotDefaults.NAME()
        name = generate_chatbot_name(repo_url=repo_url, name=name)
        chatbot = Chatbot.objects.create(
            user=request.user,
            id=uuid4(),
            name=name,
            token=str(uuid4())[:20],
            prompt_message=prompt_message,
        )

        codebase_data_source = CodebaseDataSource.objects.create(
            id=uuid4(),
            chatbot_id=chatbot.id,
            repository=repo_url,
            ingested_at=timezone.now(),
            ingestion_status="pending",
        )

        codebase_data_source_added.send(
            sender=create_via_codebase_flow,
            chatbot_id=chatbot.id,
            data_source_id=codebase_data_source.id,
        )

        return HttpResponseRedirect(
            reverse("onboarding.config", args=[str(chatbot.id)])
        )


@check_authentication
def delete_file(request, id):
    # Get the PdfDataSource object
    pdf_data_source = get_object_or_404(PdfDataSource, id=id)
    file_paths = pdf_data_source.get_files()
    if not file_paths:
        messages.error(
            request, "Folders has no value, dangerous to delete everything, exiting!"
        )
        return HttpResponseRedirect(
            reverse("chatbot.settings-data", args=[str(pdf_data_source.chatbot_id)])
        )

    # Delete the files associated with the PdfDataSource
    deleted_files = pdf_data_source.delete_files()
    if not deleted_files:
        messages.error(request, "No files deleted, exiting!")
        return HttpResponseRedirect(
            reverse("chatbot.settings-data", args=[str(pdf_data_source.chatbot_id)])
        )

    # Delete the record from the vectordatabase
    file_paths = pdf_data_source.get_files()
    if file_paths:
        for file_path in file_paths:
            # Assuming file_path is a string that contains the path to the file
            # Extract the filename from the file_path
            file_name = os.path.basename(file_path)
            # Use the filename to delete the corresponding record from the vector store
            try:
                delete_from_vector_store(
                    namespace=str(pdf_data_source.chatbot_id),
                    filter_criteria={"source": file_path},
                )
            except Exception as e:
                pass
            print(f"Deleted vector store records for file {file_name}")

    # Delete the record from the database
    pdf_data_source.delete()

    # Deleted vector store records for file.delete()
    # Add success message
    messages.success(request, deleted_files)

    return HttpResponseRedirect(
        reverse("chatbot.settings-data", args=[str(pdf_data_source.chatbot_id)])
    )
