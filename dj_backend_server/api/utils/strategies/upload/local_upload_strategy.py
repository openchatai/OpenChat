from .upload_stragegy_interface import UploadStrategy
import os
from uuid import uuid4

class LocalUploadStrategy(UploadStrategy):

    def __init__(self, upload_dir='uploads'):
        self.upload_dir = upload_dir

    def upload_files(self, files):
        uploaded_files = []
        for file in files:
            file_name = file.name
            file_ext = os.path.splitext(file_name)[1]

            # Generate unique file name
            unique_name = str(uuid4()) + file_ext  
            file_path = os.path.join(self.upload_dir, unique_name)
            
            # Create upload dir if doesn't exist
            if not os.path.exists(self.upload_dir):
                os.makedirs(self.upload_dir)

            # Save file 
            with open(file_path, 'wb+') as f:
                for chunk in file.chunks():
                    f.write(chunk)

            uploaded_file = {'name': file_name, 'path': file_path}
            uploaded_files.append(uploaded_file)

        return uploaded_files