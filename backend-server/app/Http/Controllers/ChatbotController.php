<?php

namespace App\Http\Controllers;

use App\Http\Events\ChatbotWasCreated;
use App\Http\Events\CodebaseDataSourceWasAdded;
use App\Http\Events\PdfDataSourceWasAdded;
use App\Http\Requests\CreateChatbotRequest;
use App\Http\Requests\CreateChatbotViaCodebaseRequest;
use App\Http\Requests\CreateChatbotViaPdfFlowRequest;
use App\Http\Requests\SendChatMessageRequest;
use App\Http\Requests\UpdateCharacterSettingsRequest;
use App\Http\Responses\ChatbotResponse;
use App\Http\Services\HandlePdfDataSource;
use App\Models\Chatbot;
use App\Models\ChatHistory;
use App\Models\CodebaseDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ChatbotController extends Controller
{
    /**
     * Display the chatbot index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index', [
            'chatbots' => Chatbot::all(),
        ]);
    }

    /**
     * Create a chatbot via website flow.
     *
     * @param  \App\Http\Requests\CreateChatbotRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createViaWebsiteFlow(CreateChatbotRequest $request): RedirectResponse
    {
        // Create a new Chatbot instance
        $chatbot = new Chatbot();

        // Set the properties of the chatbot
        $chatbot->setId(Uuid::uuid4());
        $chatbot->setName($request->getName());
        $chatbot->setToken(Str::random(20));
        $chatbot->setWebsite($request->getWebsite());
        $chatbot->setPromptMessage($request->getPromptMessage());

        // Save the chatbot to the database
        $chatbot->save();

        // Trigger the ChatbotWasCreated event
        event(new ChatbotWasCreated(
            $chatbot->getId(),
            $chatbot->getName(),
            $chatbot->getWebsite(),
            $chatbot->getPromptMessage(),
        ));

        // Redirect to the onboarding configuration page
        return redirect()->route('onboarding.config', ['id' => $chatbot->getId()->toString()]);
    }

    /**
     * Create a chatbot via PDF flow.
     *
     * @param  \App\Http\Requests\CreateChatbotViaPdfFlowRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createViaPdfFlow(CreateChatbotViaPdfFlowRequest $request): RedirectResponse
    {
        // Create a new Chatbot instance
        $chatbot = new Chatbot();

        // Set the properties of the chatbot
        $chatbot->setId(Uuid::uuid4());
        $chatbot->setName($request->getName());
        $chatbot->setToken(Str::random(20));
        $chatbot->setPromptMessage($request->getPromptMessage());

        // Save the chatbot to the database
        $chatbot->save();

        // Get the PDF files from the request
        $files = $request->file('pdffiles');

        // Handle the PDF data source
        $dataSource = (new HandlePdfDataSource($chatbot, $files))->handle(); // todo this should be moved to an event listener similar to the one in the previous method

        // Trigger the PdfDataSourceWasAdded event
        event(new PdfDataSourceWasAdded($chatbot->getId(), $dataSource->getId()));

        // Redirect to the onboarding configuration page
        return redirect()->route('onboarding.config', ['id' => $chatbot->getId()->toString()]);
    }

    /**
     * Update character settings for a chatbot.
     *
     * @param  \App\Http\Requests\UpdateCharacterSettingsRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCharacterSettings(UpdateCharacterSettingsRequest $request): RedirectResponse
    {
        // Get the chatbot ID from the request
        $chatbotId = $request->getChatbotId();

        // Find the chatbot by ID
        $chatbot = Chatbot::where('id', $chatbotId->toString())->firstOrFail();

        // Create or update the character settings
        $chatbot->crateOrUpdateSetting('character_name', $request->getCharacterName());

        // Redirect to the onboarding done page
        return redirect()->route('onboarding.done', ['id' => $chatbot->getId()->toString()]);
    }

    /**
     * Send a chat message to a chatbot.
     *
     * @param  \App\Http\Requests\SendChatMessageRequest  $request
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(SendChatMessageRequest $request, $token): JsonResponse
    {
        // Find the chatbot by token
        $bot = Chatbot::where('token', $token)->firstOrFail();

        // Get the question and history from the request
        $question = $request->getMessage();
        $history = $request->getHistory();
        $mode = $request->getMode();
        $initialPrompt = $bot->getPromptMessage();

        // Remove null and empty values and empty arrays or objects from the history
        $history = array_filter($history, function ($value) {
            return !is_null($value) && $value !== '' && $value !== [] && $value !== (object) [];
        });

        // Call the API to send the message to the chatbot with a timeout of 5 seconds
        $response = Http::timeout(200)->post("http://llm-server:3000/api/chat", [
            'question' => $question,
            'history' => $history,
            'namespace' => $bot->getId()->toString(),
            'mode' => $mode,
            'initial_prompt' => $initialPrompt,
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Something went wrong',
            ], 500);
        }

        // Create a ChatbotResponse instance from the API response
        $botResponse = new ChatbotResponse($response->json());


        $sessionId = Cookie::get('chatbot_' . $bot->getId()->toString());

        if (!is_null($sessionId)) {
            $history = new ChatHistory();
            $history->setId(Uuid::uuid4());
            $history->setChatbotId($bot->getId());
            $history->setFromUser();
            $history->setMessage($question);
            $history->setSessionId($sessionId);
            $history->save();

            $history = new ChatHistory();
            $history->setId(Uuid::uuid4());
            $history->setChatbotId($bot->getId());
            $history->setFromBot();
            $history->setMessage($botResponse->getBotReply());
            $history->setSessionId($sessionId);
            $history->save();
        }


        // Return the response from the chatbot
        return response()->json([
            'botReply' => $botResponse->getBotReply(),
            'sources' => $botResponse->getSourceDocuments(),
        ]);
    }

    /**
     * Display the chat view for a chatbot.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function getChatView($token)
    {
        // Find the chatbot by token
        $bot = Chatbot::where('token', $token)->firstOrFail();

        // initiate a cookie if it doesn't exist
        $cookieName = 'chatbot_' . $bot->getId()->toString();
        if (!Cookie::has($cookieName)) {
            $cookieValue = Str::random(20);
            Cookie::queue($cookieName, $cookieValue, 60 * 24 * 365);
        }

        return view('chat', [
            'bot' => $bot,
        ]);
    }

    public function createViaCodebaseFlow(CreateChatbotViaCodebaseRequest $request): RedirectResponse
    {
        $chatbot = new Chatbot();
        $chatbot->setId(Uuid::uuid4());
        $chatbot->setName($request->getName());
        $chatbot->setToken(Str::random(20));
        $chatbot->setPromptMessage($request->getPromptMessage());
        $chatbot->save();

        $datasource = new CodebaseDataSource();
        $datasource->setId(Uuid::uuid4());
        $datasource->setChatbotId($chatbot->getId());
        $datasource->setRepository($request->getRepoUrl());
        $datasource->save();

        event(new CodebaseDataSourceWasAdded(
                $chatbot->getId(),
                $datasource->getId())
        );

        return redirect()->route('onboarding.config', ['id' => $chatbot->getId()->toString()]);
    }
}
