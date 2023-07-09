<?php

namespace App\Http\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ChatbotResponse;
use App\Models\Chatbot;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
    public function sendSearchRequest(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'message' => 'required|string|max:255',
                'history' => 'sometimes|array',
            ]);

            $botToken = $request->header('X-Bot-Token');
            /** @var Chatbot $bot */
            $bot = Chatbot::where('token', $botToken)->first();

            if (!$bot) {
                return response()->json([
                    'ai_response' => "The provided bot token is invalid, make sure your connected bot is active."
                ]);
            }

            $message = $request->input('message');

            $response = Http::timeout(200)->post('http://llm-server:3000/api/chat', [
                'question' => $message,
                'namespace' => $bot->getId()->toString(),
                'mode' => "assistant",
                'initial_prompt' => $bot->getPromptMessage(),
            ]);

            $botResponse = new ChatbotResponse($response->json());

            return response()->json([
                'ai_response' => $botResponse->getBotReply()
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'ai_response' => "Something went wrong, please try again later. if this issue persists, please contact support."
            ]);
        }
    }

    public function initChat(Request $request): JsonResponse
    {
        $botToken = $request->header('X-Bot-Token');

        /** @var Chatbot $bot */
        $bot = Chatbot::where('token', $botToken)->first();

        if (!$bot) {
            return response()->json(
                [
                    "type" => "text",
                    "response" => [
                        "text" => "Could not find with token $botToken"
                    ]
                ]
            );
        }

        return response()->json(
            [
                "bot_name" => $bot->getName(),
                "logo" => "logo",
                "faq" =>  [],
                "inital_questions" => []
            ]
        );
    }

    public function sendChat(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|string|max:255',
            'history' => 'sometimes|array',
            'type' => 'in:text,button,email,emoji'
        ]);

        $botToken = $request->header('X-Bot-Token');
        /** @var Chatbot $bot */
        $bot = Chatbot::where('token', $botToken)->first();

        if (!$bot) {
            return response()->json(
                [
                    "type" => "text",
                    "response" => [
                        "text" => "I'm unable to help you at the moment, please try again later.  **code: b404**"
                    ]
                ]
            );
        }

        $message = $request->input('content');


        // otherwise, send the message to the bot
        $response = Http::timeout(200)->post('http://llm-server:3000/api/chat', [
            'question' => $message,
            'namespace' => $bot->getId()->toString(),
            'mode' => "assistant",
            'initial_prompt' => $bot->getPromptMessage(),
            'history' => $request->input('history')
        ]);

        if (is_null($response->json())) {
            return response()->json(
                [
                    "type" => "text",
                    "response" => [
                        "text" => "The request was received successfully, but the LLM server was unable to handle it, please make sure
                        your env keys are set correctly. **code: llm5XX**"
                    ]
                ]
            );
        }
        $botResponse = new ChatbotResponse($response->json());


        return response()->json(
            [
                "type" => "text",
                "response" => [
                    "text" => $botResponse->getBotReply()
                ]
            ]
        );
    }
}
