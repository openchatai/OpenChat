from django.http import JsonResponse
from django.views.decorators.http import require_POST
from api.tasks import codebase_handler_task, pdf_handler_task, website_handler_task
import json
from django.views.decorators.csrf import csrf_exempt

@csrf_exempt
@require_POST
def ingest(request):
    try:
        data = json.loads(request.body.decode('utf-8'))
        shared_folder = data.get('shared_folder')
        
        # namespace is the same as chatbot id
        namespace = data.get('namespace')
        repo_path = data.get('repo')
        type_ = data['type']

        if type_ not in ('pdf', 'website', 'codebase'):
            return JsonResponse({'error': 'Type not supported, use one of pdf, website or codebase'})

        if type_ == 'pdf':
            pdf_handler_task.delay(shared_folder, namespace)
        elif type_ == 'website':
            print("Calling website handler task")
            website_handler_task.delay(shared_folder, namespace)
        
        elif type_ == 'codebase':
            codebase_handler_task.delay(repo_path, namespace)

        return JsonResponse({'message': 'Task dispatched successfully'}, status=200)
    
    except Exception as e:
        print(e)
