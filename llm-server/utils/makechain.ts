import {OpenAI} from 'langchain/llms/openai';
import {PineconeStore} from 'langchain/vectorstores/pinecone';
import {ConversationalRetrievalQAChain} from 'langchain/chains';

export const makeChain = (vectorstore: PineconeStore, mode: string) => {

    const prompts = getInitalPrmoptByMode(mode);
    const model = new OpenAI({
        temperature: 0, // increase temepreature to get more creative answers
        modelName: 'gpt-3.5-turbo', //change this to gpt-4 if you have access
    });

    let enableSourceDocuments = false;

    if(mode === 'pair_programmer') {
        enableSourceDocuments = true;
    }
    return ConversationalRetrievalQAChain.fromLLM(model, vectorstore.asRetriever(), {
        qaTemplate: prompts.qa_prompt, questionGeneratorTemplate: prompts.condense_prompt, returnSourceDocuments: enableSourceDocuments, //The number of source documents returned is 4 by default
    },);
};

function getInitalPrmoptByMode(mode: string) {
    switch (mode) {
        case 'assistant':
            return {
                condense_prompt: `Given the following conversation and a follow up question, rephrase the follow up question to be a standalone question.

Chat History:
{chat_history}
Follow Up Input: {question}
Standalone question:`, qa_prompt: `You are a helpful AI customer support agent. Use the following pieces of context to answer the question at the end.
If you don't know the answer, just say you don't know. DO NOT try to make up an answer.
If the question is not related to the context, politely respond that you are tuned to only answer questions that are related to the context.

{context}

Question: {question}
Helpful answer in markdown:`
            };
        case 'pair_programmer':
            return {
                condense_prompt: `Given the following conversation and a follow up question, rephrase the follow up question to be a standalone question.

Chat History:
{chat_history}
Follow Up Input: {question}
Standalone question:`, qa_prompt: `You are a helpful AI programmer. you will be given the content of git repository and your should answer questions about the code in the given context. 
You must answer with code when asked to write one, and you must answer with a markdown file when asked to write one, if the question is not about the code in the context, answer with "I only answer questions about the code in the given context".

{context}

Question: {question}
Helpful answer in markdown:`
            };
        default:
            return {
                condense_prompt: `Given the following conversation and a follow up question, rephrase the follow up question to be a standalone question.

Chat History:
{chat_history}
Follow Up Input: {question}
Standalone question:`, qa_prompt: `You are a helpful AI pair programmer. You are helping a human programmer with their code. You are answering questions about the given code.
only answer questions that are about the code in the given context. If the question is not about the code in the context, answer with "I only answer questions about the code in the given context".

{context}

Question: {question}
Helpful answer in markdown:`
            };
    }
}