## To delete all older migrations
find . -path "*/migrations/*.py" -not -name "__init__.py" -delete
find . -path "*/migrations/*.pyc" -delete

## To create migrations for models [run the following from root directory]
> python manage.py makemigrations api


# Generate translations
> for web app
python manage.py makemessages -l en -i "web/*" -e html,py,js,txt
python manage.py compilemessages

> for both apps
python manage.py makemessages -l en -i "web/*" -i "api/*" -e html,py,js,txt
python manage.py compilemessages