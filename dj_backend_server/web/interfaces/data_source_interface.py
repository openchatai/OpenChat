from abc import ABC, abstractmethod

class DataSourceInterface(ABC):
    @abstractmethod
    def get_normalized_text(self) -> str:
        pass
