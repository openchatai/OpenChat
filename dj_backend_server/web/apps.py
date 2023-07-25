from django.apps import AppConfig


class ApiConfig(AppConfig):
    default_auto_field = 'django.db.models.BigAutoField'
    name = 'web'
    models_module = 'web.models.models'

    def ready(self):
        # Import signal receivers and connect them to their respective signals
        from . import events