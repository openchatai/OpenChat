import type {NextApiRequest, NextApiResponse} from 'next';
import {PINECONE_INDEX_NAME} from '@/config/pinecone';
import {DirectoryLoader} from 'langchain/document_loaders/fs/directory';
import {CustomPDFLoader} from '@/utils/customPDFLoader';
import {RecursiveCharacterTextSplitter} from 'langchain/text_splitter';
import {OpenAIEmbeddings} from 'langchain/embeddings/openai';
import {PineconeStore} from 'langchain/vectorstores/pinecone';
import {pinecone} from '@/utils/pinecone-client';

export default async function pdfHandler(req: NextApiRequest, res: NextApiResponse) {
    try {
        const {shared_folder} = req.body;
        const namespace = req.body.namespace;

        const directoryLoader = new DirectoryLoader("/app/shared_data/" + shared_folder, {
            '.pdf': (path: string | Blob) => new CustomPDFLoader(path),
        })

        const rawDocs = await directoryLoader.load();

        const textSplitter = new RecursiveCharacterTextSplitter({
            chunkSize: 1000, chunkOverlap: 200,
        });

        const docs = await textSplitter.splitDocuments(rawDocs);

        const embeddings = new OpenAIEmbeddings();
        const index = pinecone.Index(PINECONE_INDEX_NAME);

        await PineconeStore.fromDocuments(docs, embeddings, {
            pineconeIndex: index, namespace: namespace, textKey: 'text',
        });

        console.log('All is done, folder deleted');
        return res.status(200).json({message: 'Success'});
    } catch (e) {
        console.error(e);
        // @ts-ignore
        return res.status(500).json({error: e.message, line: e.lineNumber});
    }
}