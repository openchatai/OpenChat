from django.http import JsonResponse
from django.views.decorators.http import require_POST

from api.data_sources.codebase_handler import codebase_handler
from api.data_sources.website_handler import website_handler
from api.data_sources.pdf_handler import pdf_handler
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
            return pdf_handler(request)
        elif type_ == 'website':
            return website_handler(request)
        elif type_ == 'codebase':
            return codebase_handler(request)

    except Exception as e:
        return JsonResponse({
            'error': str(e),
            'line': e.__traceback__.tb_lineno,
        })
