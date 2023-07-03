<?php

namespace App\Http\Controllers;

use App\Http\Events\ChatbotWasCreated;
use App\Http\Events\CodebaseDataSourceWasAdded;
use App\Http\Events\JsonDataSourceWasAdded;
use App\Http\Events\PdfDataSourceWasAdded;
use App\Http\Requests\CreateChatbotRequest;
use App\Http\Requests\CreateChatbotViaCodebaseRequest;
use App\Http\Requests\CreateChatbotViaJsonFlowRequest;
use App\Http\Requests\CreateChatbotViaPdfFlowRequest;
use App\Http\Requests\SendChatMessageRequest;
use App\Http\Requests\UpdateCharacterSettingsRequest;
use App\Http\Responses\ChatbotResponse;
use App\Http\Services\HandleJsonDataSource;
use App\Http\Services\HandlePdfDataSource;
use App\Models\Chatbot;
use App\Models\CodebaseDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
     * Create a chatbot via Json flow.
     *
     * @param  \App\Http\Requests\CreateChatbotViaJsonFlowRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createViaJsonFlow(CreateChatbotViaJsonFlowRequest $request): RedirectResponse
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

        // Get the JSON files from the request
        $files = $request->file('jsonfiles');

        // Handle the JSON data source
        $dataSource = (new HandleJsonDataSource($chatbot, $files))->handle(); // todo this should be moved to an event listener similar to the one in the previous method

        // Trigger the JsonDataSourceWasAdded event
        event(new JsonDataSourceWasAdded($chatbot->getId(), $dataSource->getId()));

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

        // Remove null and empty values and empty arrays or objects from the history
        $history = array_filter($history, function ($value) {
            return !is_null($value) && $value !== '' && $value !== [] && $value !== (object) [];
        });

        // Call the API to send the message to the chatbot with a timeout of 5 seconds
        $response = Http::timeout(200)->post("http://llm-server:3000/api/chat", [
            'question' => $question,
            'history' => $history,
            'namespace' => $bot->getId()->toString(),
            'mode' =>  $mode,
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Something went wrong',
            ], 500);
        }

        // Create a ChatbotResponse instance from the API response
        $botResponse = new ChatbotResponse($response->json());

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

        // Render the chat view with the chatbot data
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
