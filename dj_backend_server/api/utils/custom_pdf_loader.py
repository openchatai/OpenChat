import io
from langchain.docstore.base import Document
from langchain_community.document_loaders.base import BaseLoader
from langchain_community.document_loaders import PyPDFLoader

class BufferLoader(BaseLoader):
    def __init__(self, filePathOrBlob):
        super().__init__()
        self.filePathOrBlob = filePathOrBlob

    async def load(self):
        buffer, metadata = None, {}
        if isinstance(self.filePathOrBlob, str):
            with open(self.filePathOrBlob, 'rb') as file:
                buffer = file.read()
            metadata = {'source': self.filePathOrBlob}
        else:
            buffer = await self.filePathOrBlob.read()
            metadata = {'source': 'blob', 'blobType': self.filePathOrBlob.type}
        return await self.parse(buffer, metadata)

    async def parse(self, raw, metadata):
        raise NotImplementedError("The 'parse' method must be implemented in subclasses.")

class CustomPDFLoader(BufferLoader):
    async def parse(self, raw, metadata):
        parsed = self.parse_pdf(raw)
        return [
            Document(pageContent=parsed['text'], metadata={
                **metadata, 'pdf_numpages': parsed['numpages'],
            })
        ]

    def parse_pdf(self, raw):
        pdf_loader = PyPDFLoader(io.BytesIO(raw))
        text = ""
        num_pages = pdf_loader.num_pages()
        for page_num in range(num_pages):
            text += pdf_loader.extract_text(page_num)
        return {'text': text, 'numpages': num_pages}


# Usage example:
# Replace 'filePathOrBlobValue' with the actual file path or Blob object.
# filePathOrBlobValue = "/path/to/pdf_file.pdf"
# loader = CustomPDFLoader(filePathOrBlobValue)
# docs = await loader.load()