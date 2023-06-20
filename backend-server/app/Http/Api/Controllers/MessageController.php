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

}
