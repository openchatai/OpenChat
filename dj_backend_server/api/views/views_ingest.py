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
        type_ = data['type']

        if type_ not in ('pdf', 'website', 'codebase'):
            return JsonResponse({'error': 'Type not supported'})

        if type_ == 'pdf':
            pdf_handler_task.delay(request)
        elif type_ == 'website':
            print("Calling website handler task")
            shared_folder = data.get('shared_folder')
            namespace = data.get('namespace')

            website_handler_task.delay(shared_folder, namespace)
        elif type_ == 'codebase':
            codebase_handler_task.delay(request)
        
        return JsonResponse({'status': 'Processing request'})

    except Exception as e:
        return JsonResponse({
            'error': str(e),
            'line': e.__traceback__.tb_lineno,
        })
