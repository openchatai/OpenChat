/**
 * Change the namespace to the namespace on Pinecone you'd like to store your embeddings.
 */

if (process.env.STORE === 'pinecone' && !process.env.VECTOR_STORE_INDEX_NAME) {
  throw new Error(`
    Missing required Pinecone environment variables.  
    
    Please set VECTOR_STORE_INDEX_NAME and PINECONE_API_KEY in your .env file
    to use Pinecone as your vector datastore.
    
    Alternatively, you can switch to using Qdrant by setting:
    STORE=qdrant
    QDRANT_HOST=...
    
    See docs at https://qdrant.tech/documentation/ for Qdrant configuration.
  `);
}

const VECTOR_STORE_INDEX_NAME = process.env.VECTOR_STORE_INDEX_NAME ?? '';

const PINECONE_NAME_SPACE = 'bot-test'; //namespace is optional for your vectors

export const PINECONE_TEXT_KEY = 'text';
export { VECTOR_STORE_INDEX_NAME, PINECONE_NAME_SPACE };
