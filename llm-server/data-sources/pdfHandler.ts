import type { NextApiRequest, NextApiResponse } from 'next';
import fs from 'fs';
import path from 'path';
import axios from 'axios';
import { tmpdir } from 'os';
import { PINECONE_INDEX_NAME } from '@/config/pinecone';
import { DirectoryLoader } from 'langchain/document_loaders/fs/directory';
import { CustomPDFLoader } from '@/utils/customPDFLoader';
import { RecursiveCharacterTextSplitter } from 'langchain/text_splitter';
import { TextLoader } from 'langchain/document_loaders';
import { OpenAIEmbeddings } from 'langchain/embeddings/openai';
import { PineconeStore } from 'langchain/vectorstores/pinecone';
import { pinecone } from '@/utils/pinecone-client';


function generateRandomFolderName() {
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    let folderName = '';
    for (let i = 0; i < 10; i++) {
        const randomIndex = Math.floor(Math.random() * chars.length);
        folderName += chars.charAt(randomIndex);
    }
    return folderName;
}
export default async function pdfHandler(req: NextApiRequest, res: NextApiResponse) {
    try {
        const {shared_folder} = req.body;
        const namespace = req.body.namespace;

        const  directoryLoader = new DirectoryLoader("/app/shared_data/" + shared_folder, {
            '.pdf': (path: string | Blob) => new CustomPDFLoader(path),
        });

        const rawDocs = await directoryLoader.load();

        const textSplitter = new RecursiveCharacterTextSplitter({
            chunkSize: 1000,
            chunkOverlap: 200,
        });

        const docs = await textSplitter.splitDocuments(rawDocs);

        const embeddings = new OpenAIEmbeddings();
        const index = pinecone.Index(PINECONE_INDEX_NAME);

        await PineconeStore.fromDocuments(docs, embeddings, {
            pineconeIndex: index,
            namespace: namespace,
            textKey: 'text',
        });

        console.log('All is done, folder deleted');
        return res.status(200).json({ message: 'Success' });
    } catch (e) {
        console.error(e);
        // @ts-ignore
        res.status(500).json({ error: e.message, line: e.lineNumber });
    }
}