import type {NextApiRequest, NextApiResponse} from 'next';
import {OpenAIEmbeddings} from 'langchain/embeddings/openai';
import {PineconeStore} from 'langchain/vectorstores/pinecone';
import {pinecone} from '@/utils/pinecone-client';
import {PINECONE_INDEX_NAME, PINECONE_NAME_SPACE} from '@/config/pinecone';
import {DirectoryLoader} from 'langchain/document_loaders/fs/directory';
import {CustomPDFLoader} from '@/utils/customPDFLoader';
import {RecursiveCharacterTextSplitter} from 'langchain/text_splitter';
import {TextLoader} from 'langchain/document_loaders';


export default async function handler(req: NextApiRequest, res: NextApiResponse) {
    try {
        const {type} = req.body;

        if (req.method !== 'POST') {
            return res.status(405).json({error: 'Method not allowed'});
        }

        if (!type) {
            return res.status(400).json({message: 'No type in the request'});
        }

        if (type !== 'pdf' && type !== 'website') {
            return res.status(400).json({message: 'Type not supported'});
        }

        var directoryLoader;
        var namespace = PINECONE_NAME_SPACE;

        if (type === 'pdf') {
            const {shared_folder} = req.body;
            namespace = req.body.namespace;

            directoryLoader = new DirectoryLoader("/app/shared_data/" + shared_folder, {
                '.pdf': (path: string | Blob) => new CustomPDFLoader(path),
            });
        } else if (type === 'website') {
            const {shared_folder} = req.body;
            namespace = req.body.namespace;

            directoryLoader = new DirectoryLoader("/app/shared_data/" + shared_folder, {
                '.txt': (path: any) => new TextLoader(path),
            });
        } else {
            return res.status(400).json({message: 'Not supported type'});
        }

        // const loader = new PDFLoader(filePath);
        const rawDocs = await directoryLoader.load();

        /* Split text into chunks */
        const textSplitter = new RecursiveCharacterTextSplitter({
            chunkSize: 1000, chunkOverlap: 200,
        });

        const docs = await textSplitter.splitDocuments(rawDocs);
        console.log('split docs', docs);

        console.log('creating vector store...');
        /*create and store the embeddings in the vectorStore*/
        const embeddings = new OpenAIEmbeddings();
        const index = pinecone.Index(PINECONE_INDEX_NAME); // change to your own index name

        // Embed the documents
        await PineconeStore.fromDocuments(docs, embeddings, {
            pineconeIndex: index, namespace: namespace, textKey: 'text',
        });
        console.log('Done...');
        return res.status(200).json({message: 'Success'});
    } catch (e) {
        console.error(e);
        // Return error message and line number
        // @ts-ignore
        res.status(500).json({error: e.message, line: e.lineNumber});
    }
}
