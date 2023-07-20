import type {NextApiRequest, NextApiResponse} from 'next';
import {DirectoryLoader} from 'langchain/document_loaders/fs/directory';
import {RecursiveCharacterTextSplitter} from 'langchain/text_splitter';
import {TextLoader} from 'langchain/document_loaders';
import {OpenAIEmbeddings} from 'langchain/embeddings/openai';
import { initVectorStore } from '@/utils/initVectorStore';

export default async function websiteHandler(req: NextApiRequest, res: NextApiResponse) {
    try {
        const {shared_folder} = req.body;
        const namespace = req.body.namespace;

        const directoryLoader = new DirectoryLoader("/app/shared_data/" + shared_folder, {
            '.txt': (path) => new TextLoader(path),
        });

        const rawDocs = await directoryLoader.load();

        const textSplitter = new RecursiveCharacterTextSplitter({
            chunkSize: 1000, chunkOverlap: 200,
        });

        const docs = await textSplitter.splitDocuments(rawDocs);

        const embeddings = new OpenAIEmbeddings();

        await initVectorStore(docs, embeddings, {namespace})
        console.log('All is done, folder deleted');
        return res.status(200).json({message: 'Success'});
    } catch (e) {
        console.error(e);
        // @ts-ignore
        return res.status(500).json({error: e.message, line: e.lineNumber});
    }
}