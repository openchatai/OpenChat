import { Document } from "langchain/document";
import { PineconeStore } from "langchain/vectorstores/pinecone";
import { QdrantVectorStore } from "langchain/vectorstores/qdrant";
import { StoreType } from "./store.enum";
import { OpenAIEmbeddings } from "langchain/embeddings/openai";
import { StoreOptions } from "@/interfaces/storeOptions.interface";
import { PINECONE_TEXT_KEY } from "@/config/pinecone";

export async function initVectorStore(
  docs:  Document[],
  embeddings: OpenAIEmbeddings,
  options: StoreOptions,
) {
  switch (process.env.STORE) {
    case StoreType.PINECONE:
      await PineconeStore.fromDocuments(docs, embeddings, {
        pineconeIndex: options.index!,
        namespace: options.namespace,
        textKey: PINECONE_TEXT_KEY,
      });
      break;

    case StoreType.QDRANT:
      await QdrantVectorStore.fromDocuments(docs, embeddings, {
        collectionName: options.namespace,
        url: process.env.QDRANT_URL,
      });
      break;

    // Invalid store type
    default:
      const validStores = Object.values(StoreType).join(', ');
      throw new Error(
        `Invalid STORE environment variable value: ${process.env.STORE}
          Valid values are: ${validStores}`,
      );
  }
}
