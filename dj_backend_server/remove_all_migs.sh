#!/bin/bash

# Stop on errors
set -e

# Activate virtualenv if needed
# source env/bin/activate

# Delete migration files
find . -path "*/migrations/*.py" -not -name "__init__.py" -delete
find . -path "*/migrations/*.pyc"  -delete

# Drop tables
python manage.py dbshell
DROP SCHEMA public CASCADE; CREATE SCHEMA public;

# Re-initialize 
python manage.py makemigrations
python manage.py migrate

# Recreate admin user if using custom user model
python manage.py createsuperuser --username admin