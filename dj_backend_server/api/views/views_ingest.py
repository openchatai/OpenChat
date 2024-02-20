import json
from django.http import JsonResponse
from django.views.decorators.http import require_POST
from django.views.decorators.csrf import csrf_exempt
from api.tasks import codebase_handler_task, pdf_handler_task, website_handler_task


@csrf_exempt
@require_POST
def ingest(request):
    """
    This view function handles the ingestion of different types of data (PDFs, websites, or codebases). It retrieves the data type and
    other parameters from the POST data of the request, validates them, and dispatches a task to handle the data ingestion based on the
    data type.

    Args:
        request (HttpRequest): The HTTP request object. The data type and other parameters are expected to be in the POST data of this
        request.

    Returns:
        JsonResponse: A JSON response containing a success message and a 200 status code if the task was dispatched successfully, or
        an error message if the data type is not supported or if an exception was raised.
    """
    try:
        data = json.loads(request.body.decode("utf-8"))
        shared_folder = data.get("shared_folder")

        # namespace is the same as chatbot id
        namespace = data.get("namespace")
        repo_path = data.get("repo")
        metadata = data.get("metadata")
        type_ = data["type"]

        if type_ not in ("pdf", "website", "codebase"):
            return JsonResponse(
                {"error": "Type not supported, use one of pdf, website or codebase"}
            )

        if type_ == "pdf":
            delete_folder_flag = data.get("delete_folder_flag", False)
            ocr_pdf_file = data.get("ocr_pdf_file", False)
            pdf_handler_task.delay(
                shared_folder, namespace, delete_folder_flag, ocr_pdf_file, metadata
            )
        elif type_ == "website":
            print("Calling website handler task")
            website_handler_task.delay(shared_folder, namespace, metadata)

        elif type_ == "codebase":
            codebase_handler_task.delay(repo_path, namespace, metadata)

        return JsonResponse({"message": "Task dispatched successfully"}, status=200)

    except Exception as e:
        print(e)
