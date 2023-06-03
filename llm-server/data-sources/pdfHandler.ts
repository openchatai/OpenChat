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
        const { pdf_files, namespace } = req.body;

        const folderName = generateRandomFolderName();
        const folderPath = path.join(tmpdir(), folderName);

        // Create the folder
        fs.mkdirSync(folderPath);

        // Download each PDF file
        for (let i = 0; i < pdf_files.length; i++) {
            const url = pdf_files[i];
            const fileName = `file${i + 1}.pdf`;
            const filePath = path.join(folderPath, fileName);

            // Download the file using axios
            const response = await axios.get(url, { responseType: 'stream' });
            const writer = fs.createWriteStream(filePath);
            response.data.pipe(writer);

            // Wait for the file to finish downloading
            await new Promise((resolve, reject) => {
                writer.on('finish', resolve);
                writer.on('error', reject);
            });
        }

        const directoryLoader = new DirectoryLoader(folderPath, {
            '.pdf': (path) => new CustomPDFLoader(path),
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

        fs.rmdirSync(folderPath, { recursive: true });
        console.log('All is done, folder deleted');
        return res.status(200).json({ message: 'Success' });
    } catch (e) {
        console.error(e);
        // @ts-ignore
        res.status(500).json({ error: e.message, line: e.lineNumber });
    }
}