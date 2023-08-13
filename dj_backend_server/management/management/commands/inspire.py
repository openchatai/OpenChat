# management/commands/inspire.py
from django.core.management.base import BaseCommand
from django.utils.translation import gettext as _
from random import choice
from django.conf import settings

class Command(BaseCommand):
    help = _("Displays an inspiring quote")

    def handle(self, *args, **options):
        inspiring_quotes = getattr(settings, 'INSPIRING_QUOTES', [])
        quote = choice(inspiring_quotes) if inspiring_quotes else _("Don't forget to set INSPIRING_QUOTES in settings.py!")
        self.stdout.write(self.style.SUCCESS(quote))
