import os
from django.core.management.base import BaseCommand
from django.contrib.auth.models import User
from dotenv import load_dotenv

load_dotenv()


class Command(BaseCommand):
    help = 'Create a superuser if one does not exist'

    def handle(self, *args, **options):
        # Get the username and password from environment variables, if available
        username = os.environ.get('DJANGO_SUPERUSER_USERNAME', 'admin')
        password = os.environ.get('DJANGO_SUPERUSER_PASSWORD', 'admin_password')

        if not User.objects.filter(username=username).exists():
            User.objects.create_superuser(username, '', password)
            self.stdout.write(self.style.SUCCESS('Superuser created successfully'))
        else:
            self.stdout.write(self.style.SUCCESS('Superuser already exists'))
