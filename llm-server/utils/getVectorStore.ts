import { StoreOptions } from '@/interfaces/storeOptions.interface';
import { StoreType } from './store.enum';
import { OpenAIEmbeddings } from 'langchain/embeddings/openai';
import { PineconeStore } from 'langchain/vectorstores/pinecone';
import { QdrantVectorStore } from 'langchain/vectorstores/qdrant';
import { VectorStore } from 'langchain/dist/vectorstores/base';
import { PINECONE_TEXT_KEY, VECTOR_STORE_INDEX_NAME } from '@/config/pinecone';

export async function getVectorStore(
  options: StoreOptions,
): Promise<VectorStore> {
  let vectorStore: VectorStore;

  switch (process.env.STORE) {
    case StoreType.PINECONE:
      const { pinecone } = await import('./pinecone-client');
      const pineconeIndex = pinecone.Index(VECTOR_STORE_INDEX_NAME);

      vectorStore = await PineconeStore.fromExistingIndex(
        new OpenAIEmbeddings({}),
        {
          pineconeIndex,
          namespace: options.namespace,
          textKey: PINECONE_TEXT_KEY,
        },
      );
      break;

    case StoreType.QDRANT:
      vectorStore = await QdrantVectorStore.fromExistingCollection(
        new OpenAIEmbeddings({}),
        {
          collectionName: options.namespace,
          url: process.env.QDRANT_URL,
        },
      );
      break;

    default:
      throw new Error('Invalid STORE environment variable value');
  }

  return vectorStore;
}
