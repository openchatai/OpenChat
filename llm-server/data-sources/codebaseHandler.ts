import type {NextApiRequest, NextApiResponse} from 'next';
import {RecursiveCharacterTextSplitter} from 'langchain/text_splitter';
import {OpenAIEmbeddings} from 'langchain/embeddings/openai';
import {GithubRepoLoader} from "langchain/document_loaders/web/github";
import { initVectorStore } from '@/utils/initVectorStore';

export default async function codebaseHandler(req: NextApiRequest, res: NextApiResponse) {
    try {
        const {repo, namespace} = req.body;
        const loader = new GithubRepoLoader(repo, // @ts-ignore
            {
                branch: "main", recursive: true, unknown: "warn", // @ts-ignore
                // ignorePaths: ['node_modules', 'vendor', 'bower_components', '__pycache__', '.venv', 'target', 'build', 'bin', 'obj', 'tmp', 'dist', 'public', '.git', '.svn', 'CVS', 'out', 'logs', '.idea', '.vscode', '.gradle', '.classpath', '.project', '.settings', '.DS_Store', 'venv', 'env', 'migrations', 'db', 'log', 'logs', 'backup', 'cache', 'temp', 'tmp', 'docs', 'doc', 'test', 'tests', 'spec', 'specs']
            });
        const rawDocs = await loader.load();

        console.log('Loaded documents')

        const textSplitter = new RecursiveCharacterTextSplitter({
            chunkSize: 1000, chunkOverlap: 200,
        });
        const docs = await textSplitter.splitDocuments(rawDocs);

        console.log('Split documents')

        const embeddings = new OpenAIEmbeddings();

        await initVectorStore(docs, embeddings, {namespace})

        console.log('Indexed documents. all done!')
        return res.status(200).json({message: 'Success'});
    } catch (e) {
        console.error(e);
        // @ts-ignore
        return res.status(500).json({error: e.message, line: e.lineNumber});
    }
}