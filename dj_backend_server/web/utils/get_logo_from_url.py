import os
import hashlib
import requests
from urllib.parse import urlparse
from django.core.files.base import ContentFile
from django.core.files.storage import FileSystemStorage

def get_logo_from_url(url):
    try:
        # Extract domain name from URL
        domain = urlparse(url).netloc

        # Make request to Clearbit API using Python's requests library
        response = requests.get(f'https://logo.clearbit.com/{domain}')

        # Check if request was successful
        if response.status_code == 200:
            # Generate hashed name for logo file
            logo_name = hashlib.md5(domain.encode('utf-8')).hexdigest() + '.png'

            # Save logo file using Django's FileSystemStorage
            logo_storage = FileSystemStorage()
            logo_path = os.path.join('website_data_sources/icons/', logo_name)
            logo_file = ContentFile(response.content)
            icon_path = logo_storage.save(logo_path, logo_file)


            print("icon path -> ", icon_path)

            # Return logo file name
            return logo_name
        else:
            return None
    except Exception as e:
        return None
