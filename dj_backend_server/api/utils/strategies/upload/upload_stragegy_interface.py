from abc import ABC, abstractmethod

class UploadStrategy(ABC):

    @abstractmethod
    def upload_files(self, files):
        pass