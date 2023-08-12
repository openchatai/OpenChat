from django.core.management.base import BaseCommand
from django.db import connections
from django.db.utils import OperationalError
from django.core.management import call_command

class Command(BaseCommand):
    help = 'Run conditional migrations for the web app'

    def handle(self, *args, **options):
        db_conn = connections['default']

        try:
            c = db_conn.cursor()
            # Check if 'chatbots' table exists
            c.execute("SHOW TABLES LIKE 'chatbots'")
            chatbots_table_exists = bool(c.fetchone())
        except OperationalError:
            self.stdout.write(self.style.ERROR("Error while checking tables"))
            return

        if not chatbots_table_exists:
            self.stdout.write(self.style.WARNING("Table 'chatbots' not found, running normal migration..."))
            call_command("makemigrations", "web")  # Run migrations for the 'web' app
            call_command("migrate")
        else:
            self.stdout.write(self.style.SUCCESS("Table 'chatbots' exists, running fake migration..."))
            call_command("migrate", "web", fake=True)  # Fake migrations for the 'web' app

        self.stdout.write(self.style.SUCCESS("Conditional migration completed"))
