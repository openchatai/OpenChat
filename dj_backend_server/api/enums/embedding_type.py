from enum import Enum


class EmbeddingProvider(Enum):
    OPENAI = "openai"
    BARD = "bard"
    azure = "azure"
    llama2 = "llama2"
    ollama = "ollama"

