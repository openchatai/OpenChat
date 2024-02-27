from api.utils.strategies.upload.local_upload_strategy import LocalUploadStrategy
from api.utils.strategies.upload.minio_upload_strategy import MinioUploadStrategy

class UploadStrategyFactory:

    @staticmethod
    def get_strategy(strategy_type):
        if strategy_type == 'local':
            return LocalUploadStrategy()
        elif strategy_type == 'minio':
            return MinioUploadStrategy()
        else:
            raise ValueError(f'Invalid strategy {strategy_type}')