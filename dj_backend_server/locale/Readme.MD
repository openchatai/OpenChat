In your docker do this:
Do for each language you have.
'''bash
python manage.py makemessages -l en --ignore=venv/*
'''bash
python manage.py makemessages -l ro --ignore=venv/*
'''bash
python manage.py compilemessages