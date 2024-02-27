from minio import Minio
from minio.error import ResponseError
from .upload_stragegy_interface import UploadStrategy
from uuid import uuid4
import os

class MinioUploadStrategy(UploadStrategy):

    def __init__(self, endpoint, access_key, secret_key, bucket_name):
        self.minio_client = Minio(endpoint, access_key=access_key, secret_key=secret_key, secure=True)
        self.bucket_name = bucket_name

    def upload_files(self, files):
        uploaded_files = []
        try:
            for file in files:
                file_name = file.name
                file_ext = os.path.splitext(file_name)[1]

                # Generate unique file name
                unique_name = str(uuid4()) + file_ext

                # Upload file 
                self.minio_client.fput_object(self.bucket_name, unique_name, file)

                uploaded_file = {'name': file_name, 'path': unique_name}
                uploaded_files.append(uploaded_file)
        
        except ResponseError as err:
            print(err)

        return uploaded_files