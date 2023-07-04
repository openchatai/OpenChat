import type {NextApiRequest, NextApiResponse} from 'next';
import {OpenAIEmbeddings} from 'langchain/embeddings/openai';
import {PineconeStore} from 'langchain/vectorstores/pinecone';
import {makeChain} from '@/utils/makechain';
import {pinecone} from '@/utils/pinecone-client';
import {PINECONE_INDEX_NAME, PINECONE_NAME_SPACE} from '@/config/pinecone';

export default async function handler(req: NextApiRequest, res: NextApiResponse,) {
    const {question, history, namespace, mode, initial_prompt} = req.body;

    console.log('req.body', req.body);
    console.log({question, history, namespace, mode, initial_prompt});
    //only accept post requests
    if (req.method !== 'POST') {
        return res.status(405).json({error: 'Method not allowed'});
    }

    if (!question) {
        return res.status(400).json({message: 'No question in the request'});
    }
    // OpenAI recommends replacing newlines with spaces for best results
    const sanitizedQuestion = question.trim().replaceAll('\n', ' ');

    try {
        const index = pinecone.Index(PINECONE_INDEX_NAME);

        /* create vectorstore*/
        const vectorStore = await PineconeStore.fromExistingIndex(new OpenAIEmbeddings({}), {
            pineconeIndex: index, textKey: 'text', namespace: namespace, //namespace comes from your config folder
        },);

        //create chain
        const chain = makeChain(vectorStore, mode, initial_prompt);
        //Ask a question using chat history
        const response = await chain.call({
            question: sanitizedQuestion, chat_history: history || [],
        });

        console.log('response', response);
        return res.status(200).json(response);
    } catch (error: any) {
        console.log('error', error);
        return res.status(500).json({error: error.message || 'Something went wrong'});
    }
}