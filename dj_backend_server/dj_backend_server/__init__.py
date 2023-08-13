from .celery import app as celery_app

import pymysql
pymysql.install_as_MySQLdb()
