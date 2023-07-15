import { VectorOperationsApi } from "@pinecone-database/pinecone/dist/pinecone-generated-ts-fetch";

export interface StoreOptions {
    namespace?: string;
    index?: VectorOperationsApi;
  }