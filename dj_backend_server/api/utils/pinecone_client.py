from langchain.vectorstores.pinecone import Pinecone
class PineconeSingleton:
    _instance = None

    @classmethod
    def get_instance(cls):
        if cls._instance is None:
            cls._instance = Pinecone()
        return cls._instance