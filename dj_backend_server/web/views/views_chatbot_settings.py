# chatbot/views.py

import os
from django.shortcuts import render, redirect, get_object_or_404
from django.core.exceptions import ValidationError
from django.http import HttpResponse, Http404, HttpRequest
from django.db.models import Count, Min
from django.http import HttpResponseNotFound, FileResponse
from django.conf import settings
from django.core.files.storage import default_storage
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from web.models.crawled_pages import CrawledPages
from web.models.chatbot import Chatbot
from web.models.chat_histories import ChatHistory
from web.models.website_data_sources import WebsiteDataSource
from web.models.pdf_data_sources import PdfDataSource
from web.models.codebase_data_sources import CodebaseDataSource
from web.models.chatbot_settings import ChatbotSetting
from web.enums.chatbot_initial_prompt_enum import ChatBotInitialPromptEnum
from api.utils.init_vector_store import delete_vector_store_namespace


def image_view(request, app_id, image_name):
    """
    This view function serves an image file from the 'website_data_sources/icons' directory. It constructs the full image path by
    joining 'website_data_sources/icons' with the provided image name, checks if the image file exists, and returns a FileResponse with
    the image file if it exists. If the image file does not exist, it returns an HttpResponseNotFound.

    Args:
        request (HttpRequest): The HTTP request object.
        app_id (int): The ID of the app whose image is to be served. Currently, this argument is not used.
        image_name (str): The name of the image file in the 'website_data_sources/icons' directory.

    Returns:
        FileResponse: If the image file exists. The response includes the image file.
        HttpResponseNotFound: If the image file does not exist.
    """
    image_path = os.path.join("website_data_sources/icons", image_name)
    if os.path.exists(image_path):
        return FileResponse(open(image_path, "rb"))
    else:
        return HttpResponseNotFound()


def general_settings(request, id):
    """
    This view function retrieves the general settings for a specific chatbot. It fetches the chatbot by its ID and renders a settings
    page with the fetched chatbot.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot whose general settings are to be retrieved.

    Returns:
        HttpResponse: An HTTP response with a rendered settings page that includes the fetched chatbot.
    """
    bot = get_object_or_404(Chatbot, id=id)
    return render(request, "settings.html", {"bot": bot})


def delete_bot(request, id):
    """
    This view function deletes a specific chatbot. It fetches the chatbot by its ID and deletes it. After the chatbot is deleted,
    it redirects to the index page.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot to be deleted.

    Returns:
        HttpResponseRedirect: An HTTP response that redirects to the index page.
    """
    bot = get_object_or_404(Chatbot, id=id)
    delete_vector_store_namespace(namespace=str(bot.id))
    # Delete related records from the database
    ChatHistory.objects.filter(chatbot_id=id).delete()
    ChatbotSetting.objects.filter(chatbot_id=id).delete()
    CrawledPages.objects.filter(website_data_source__chatbot_id=id).delete()
    CodebaseDataSource.objects.filter(chatbot_id=id).delete()
    WebsiteDataSource.objects.filter(chatbot_id=id).delete()
    PdfDataSource.objects.filter(chatbot_id=id).delete()
    # Delete the bot from the database
    bot.delete()
    return redirect("index")


def serve_website_data_source_file(request, file_path):
    """
    This view function serves a file from the 'website_data_sources' directory. It constructs the full file path by joining
    'website_data_sources' with the provided file path, checks if the file exists, and returns a FileResponse with the file
    if it exists. If the file does not exist, it returns an HttpResponseNotFound.

    Args:
        request (HttpRequest): The HTTP request object.
        file_path (str): The relative path to the file in the 'website_data_sources' directory.

    Returns:
        FileResponse: If the file exists. The response includes the file.
        HttpResponseNotFound: If the file does not exist.
    """
    file_path = os.path.join("website_data_sources", file_path)
    if os.path.exists(file_path):
        return FileResponse(open(file_path, "rb"))
    else:
        return HttpResponseNotFound()


def general_settings_update(request, id):
    """
    This view function updates the general settings for a specific chatbot. It fetches the chatbot by its ID, validates the POST data
    from the request, updates the chatbot's name, website, and prompt message with the validated POST data, saves the chatbot, and
    redirects to the chatbot's settings page. If the request method is not POST, it returns an HTTP response with a "Method not allowed."
    message and a 405 status code.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot whose general settings are to be updated.

    Raises:
        ValidationError: If the name field in the POST data is empty.
        ValidationError: If the website field in the POST data is empty.

    Returns:
        HttpResponseRedirect: If the request method is POST and the chatbot's general settings are successfully updated. The response
        redirects to the chatbot's settings page.
        HttpResponse: If the request method is not POST. The response includes a "Method not allowed." message and a 405 status code.
    """
    bot = get_object_or_404(Chatbot, id=id)

    if request.method == "POST":
        name = request.POST.get("name")
        if not name:
            raise ValidationError("Name field is required.")
        website = request.POST.get("website")
        if not website:
            raise ValidationError("Website field is required.")

        bot.name = name
        bot.website = website
        bot.prompt_message = request.POST.get(
            "prompt_message", ChatBotInitialPromptEnum.AI_ASSISTANT_INITIAL_PROMPT.value
        )
        bot.save()
        return redirect("chatbot.settings", id=id)

    return HttpResponse("Method not allowed.", status=405)


def history_settings(request, id):
    """
    This view function retrieves the chat history settings for a specific chatbot. It fetches the chatbot by its ID, aggregates the
    chat history by the session ID, counts the total messages, finds the date of the first message for each session, filters the
    aggregated chat history by the chatbot ID, orders the aggregated chat history by the date of the first message in descending order,
    and renders a history settings page with the fetched chatbot and the aggregated chat history.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot whose chat history settings are to be retrieved.

    Returns:
        HttpResponse: An HTTP response with a rendered history settings page that includes the fetched chatbot and the aggregated chat history.
    """
    bot = get_object_or_404(Chatbot, id=id)
    chat_history = (
        ChatHistory.objects.values("session_id")
        .annotate(total_messages=Count("*"), first_message=Min("created_at"))
        .filter(chatbot_id=bot.id)
        .order_by("-first_message")
    )
    return render(
        request, "settings-history.html", {"bot": bot, "chatHistory": chat_history}
    )


def get_history_by_session_id(request, id, session_id):
    """
    This view function retrieves the chat history for a specific chatbot and session. It fetches the chatbot by its ID, filters the
    chat history by the chatbot ID and the session ID, orders the chat history by the creation date, and renders a chat history page
    with the fetched chat history.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot whose chat history is to be retrieved.
        session_id (str): The session ID for which the chat history is to be retrieved.

    Raises:
        Http404: If the chatbot with the given ID does not exist.

    Returns:
        HttpResponse: An HTTP response with a rendered chat history page that includes the fetched chat history.
    """
    try:
        bot = Chatbot.objects.get(id=id)
    except Chatbot.DoesNotExist:
        raise Http404("Chatbot does not exist.")
    chat_history = ChatHistory.objects.filter(
        chatbot_id=bot.id, session_id=session_id
    ).order_by("created_at")
    if not chat_history:
        print(f"No chat history found for session_id {session_id}.")
    return render(request, "widgets/chat-history.html", {"chatHistory": chat_history})


def data_settings(request, id):
    """
    This view function retrieves the data settings for a specific chatbot. It fetches the chatbot and its associated data sources
    (websites, PDFs, and codebases), processes the PDF data sources, and renders a settings page with the fetched data.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot whose data settings are to be retrieved.

    Returns:
        HttpResponse: An HTTP response with a rendered settings page that includes the chatbot and its associated data sources.
    """
    bot = get_object_or_404(Chatbot, id=id)
    website_data_sources = (
        WebsiteDataSource.objects.filter(chatbot_id=id)
        .prefetch_related("crawled_pages")
        .order_by("-id")
    )
    pdf_data_sources = PdfDataSource.objects.filter(chatbot_id=id).order_by("-id")
    codebase_data_sources = CodebaseDataSource.objects.filter(chatbot_id=id).order_by(
        "-id"
    )

    website_page = request.GET.get("website_page", 1)
    pdf_page = request.GET.get("pdf_page", 1)
    codebase_page = request.GET.get("codebase_page", 1)

    website_paginator = Paginator(website_data_sources, 25)
    pdf_paginator = Paginator(
        PdfDataSource.objects.filter(chatbot_id=id).order_by("-id"), 25
    )
    codebase_paginator = Paginator(
        CodebaseDataSource.objects.filter(chatbot_id=id).order_by("-id"), 25
    )
    crawled_pages_count = CrawledPages.objects.filter(
        website_data_source__chatbot_id=id
    ).count()
    try:
        website_data_sources = website_paginator.page(website_page)
    except PageNotAnInteger:
        website_data_sources = website_paginator.page(1)
    except EmptyPage:
        website_data_sources = website_paginator.page(website_paginator.num_pages)

    try:
        pdf_data_sources = pdf_paginator.page(pdf_page)
    except PageNotAnInteger:
        pdf_data_sources = pdf_paginator.page(1)
    except EmptyPage:
        pdf_data_sources = pdf_paginator.page(pdf_paginator.num_pages)

    try:
        codebase_data_sources = codebase_paginator.page(codebase_page)
    except PageNotAnInteger:
        codebase_data_sources = codebase_paginator.page(1)
    except EmptyPage:
        codebase_data_sources = codebase_paginator.page(codebase_paginator.num_pages)

    for source in pdf_data_sources:
        merged_files = []
        source.pdf_exists = False
        source.txt_exists = False

        for file_info, file_url in zip(source.get_files_info(), source.get_files()):

            if os.path.exists(file_url):
                source.pdf_exists = True
                full_file_url = os.environ.get("APP_URL") + "/" + file_url
                merged_file = {
                    "name": file_info.get("original_name", ""),
                    "url": full_file_url,
                    "message": '<span class="material-symbols-outlined">download</span>',
                    "txt_exists": False,
                    "txt_content": "",
                }
                txt_file_path = os.path.splitext(file_url)[0] + ".txt"
                if default_storage.exists(txt_file_path):
                    source.txt_exists = True
                    merged_file["txt_exists"] = True
                    with default_storage.open(txt_file_path, "rb") as txt_file:
                        raw_content = txt_file.read(
                            2000
                        )  # Read only the first 2000 characters
                        try:
                            merged_file["txt_content"] = raw_content.decode("utf-8")
                        except UnicodeDecodeError:
                            merged_file["txt_content"] = raw_content.decode(
                                "iso-8859-1"
                            )

            else:
                merged_file = {
                    "name": file_info.get("original_name", ""),
                    "url": "javascript:void(0)",
                    "message": '<span class="material-symbols-outlined">remove_selection</span>',
                }
            merged_files.append(merged_file)

        status_html = None
        if source.ingest_status == "pending":
            status_html = '<div class="inline-flex font-medium bg-blue-100 text-blue-600 rounded-full text-center px-2.5 py-0.5">PENDING</div>'
        elif source.ingest_status == "success":
            status_html = '<div class="inline-flex font-medium bg-emerald-100 text-emerald-600 rounded-full text-center px-2.5 py-0.5">SUCCESS</div>'
        elif source.ingest_status == "failed":
            status_html = '<div class="inline-flex font-medium bg-amber-100 text-amber-600 rounded-full text-center px-2.5 py-0.5">FAILED</div>'

        source.merged_files = merged_files
        source.status_html = status_html

    return render(
        request,
        "settings-data.html",
        {
            "bot": bot,
            "website_data_sources": website_data_sources,
            "pdf_data_sources": pdf_data_sources,
            "codebase_data_sources": codebase_data_sources,
            "crawled_pages_count": crawled_pages_count,
        },
    )


def analytics_settings(request, id):
    """
    Renders the analytics settings page for a specific chatbot.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot.

    Returns:
        HttpResponse: The rendered analytics settings page.
    """
    bot = get_object_or_404(Chatbot, id=id)
    data_sources = bot.website_data_sources.all()
    return render(
        request, "settings-analytics.html", {"bot": bot, "dataSources": data_sources}
    )


def integrations_settings(request, id):
    """
    This function handles the integrations settings for a chatbot.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot.

    Returns:
        HttpResponse: The HTTP response object.
    """
    bot = get_object_or_404(Chatbot, id=id)
    return render(request, "settings-integrations.html", {"bot": bot})


def data_sources_updates(request, id):
    """
    This function retrieves the website and PDF data sources associated with a chatbot and renders a template with the data.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot.

    Returns:
        HttpResponse: The rendered template with the website and PDF data sources.
    """
    # chatbot = get_object_or_404(Chatbot, id=id)
    website_data_sources = WebsiteDataSource.objects.filter(chatbot_id=id)
    pdf_data_sources = PdfDataSource.objects.filter(chatbot_id=id)
    return render(
        request,
        "widgets/data-sources-updates.html",
        {
            "website_data_sources": website_data_sources,
            "pdf_data_sources": pdf_data_sources,
        },
    )


def theme_settings(request, id):
    """Renders the theme settings page for a chatbot.

    Args:
        request (HttpRequest): The HTTP request object.
        id (int): The ID of the chatbot.

    Returns:
        HttpResponse: The HTTP response object containing the rendered theme settings page.
    """
    bot = get_object_or_404(Chatbot, id=id)
    context = {"APP_URL": settings.APP_URL, "bot": bot}
    return render(request, "settings-theme.html", context)
