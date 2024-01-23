from django.conf import settings

def app_url(request):
    return {'APP_URL': settings.APP_URL}