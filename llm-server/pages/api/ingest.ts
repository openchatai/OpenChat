import type {NextApiRequest, NextApiResponse} from 'next';
import pdfHandler from '@/data-sources/pdfHandler';
import websiteHandler from '@/data-sources/websiteHandler';
import codebaseHandler from "@/data-sources/codebaseHandler";

export default async function handler(req: NextApiRequest, res: NextApiResponse) {
    try {
        const {type} = req.body;

        if (req.method !== 'POST') {
            return res.status(405).json({error: 'Method not allowed'});
        }

        if (!type) {
            return res.status(400).json({message: 'No type in the request'});
        }

        const supportedTypes = ['pdf', 'website', 'codebase'];
        if (!supportedTypes.includes(type)) {
            return res.status(400).json({message: 'Type not supported'});
        }

        if (type === 'pdf') {
            return await pdfHandler(req, res);
        } else if (type === 'website') {
            return await websiteHandler(req, res);
        } else if (type === 'codebase') {
            return await codebaseHandler(req, res);
        } else {
            return res.status(400).json({message: 'Not supported type'});
        }

    } catch (e) {
        console.error(e);
        // Return error message and line number
        // @ts-ignore
        return res.status(500).json({error: e.message, line: e.lineNumber});
    }
}