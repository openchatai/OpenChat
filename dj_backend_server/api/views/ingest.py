from django.http import JsonResponse
from django.views.decorators.http import require_POST

from api.data_sources import codebase_handler, pdf_handler, website_handler
import json

@require_POST
def handler(request):
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
