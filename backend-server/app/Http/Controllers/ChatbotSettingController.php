<?php

namespace App\Http\Controllers;

use App\Http\Enums\ChatBotInitialPromptEnum;
use App\Models\Chatbot;
use App\Models\ChatHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ChatbotSettingController extends Controller
{
    /**
     * Display the general settings page for a chatbot.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function generalSettings($id)
    {
        // Find the chatbot by ID
        $bot = Chatbot::where('id', $id)->firstOrFail();

        return view('settings', [
            'bot' => $bot,
        ]);
    }

    public function deleteBot($id): RedirectResponse
    {
        $bot = Chatbot::where('id', $id)->firstOrFail();
        $bot->delete();

        return redirect()->route('index')->with('success', 'Bot deleted!');
    }

    /**
     * @throws ValidationException
     */
    public function generalSettingsUpdate(Request $request, $id)
    {
        // Find the chatbot by ID
        $bot = Chatbot::where('id', $id)->firstOrFail();

        // Validate the request
        $this->validate($request, [
            'name' => 'required',
        ]);

        // Update the chatbot name
        $bot->setName($request->input('name'));
        $bot->setPromptMessage($request->input('prompt_message', ChatBotInitialPromptEnum::AI_ASSISTANT_INITIAL_PROMPT));
        $bot->save();

        return redirect()->route('chatbot.settings', ['id' => $bot->getId()])->with('success', 'Settings updated!');
    }

    public function historySettings(Request $request, $id)
    {
        /** @var Chatbot $bot */
        $bot = Chatbot::where('id', $id)->firstOrFail();

        $chatHistory = ChatHistory::select('session_id', DB::raw('COUNT(*) as total_messages'))
            ->selectRaw('MIN(created_at) as first_message')
            ->where('chatbot_id', $bot->getId())
            ->groupBy('session_id')
            ->orderBy('first_message', 'desc')
            ->get();

        return view('settings-history', [
            'bot' => $bot,
            'chatHistory' => $chatHistory,
        ]);
    }

    public function getHistoryBySessionId(Request $request, $id, $sessionId)
    {
        /** @var Chatbot $bot */
        $bot = Chatbot::where('id', $id)->firstOrFail();

        $chatHistory = ChatHistory::where('chatbot_id', $bot->getId())
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('widgets.chat-history', [
            'chatHistory' => $chatHistory,
        ]);
    }

    public function dataSettings(Request $request, $id)
    {
        // Find the chatbot by ID
        $bot = Chatbot::where('id', $id)->firstOrFail();

        // Get website data sources and PDF data sources for the chatbot
        $websiteDataSources = $bot->getWebsiteDataSources()->get();
        $pdfDataSources = $bot->getPdfFilesDataSources()->get();
        $codebaseDataSources = $bot->getCodebaseDataSources()->get();

        return view('settings-data', [
            'bot' => $bot,
            'websiteDataSources' => $websiteDataSources,
            'pdfDataSources' => $pdfDataSources,
            'codebaseDataSources' => $codebaseDataSources,
        ]);
    }

    /**
     * Display the analytics settings page for a chatbot.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function analyticsSettings(Request $request, $id)
    {
        // Find the chatbot by ID
        $bot = Chatbot::where('id', $id)->firstOrFail();

        // Get website data sources for the chatbot
        $dataSources = $bot->getWebsiteDataSources()->get();

        return view('settings-analytics', [
            'bot' => $bot,
            'dataSources' => $dataSources,
        ]);
    }

    /**
     * Display the integrations settings page for a chatbot.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function integrationsSettings(Request $request, $id)
    {
        // Find the chatbot by ID
        $bot = Chatbot::where('id', $id)->firstOrFail();

        return view('settings-integrations', [
            'bot' => $bot,
        ]);
    }

    /**
     * Display the data sources updates widget for a chatbot.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function dataSourcesUpdates($id)
    {
        // Find the chatbot by ID
        $bot = Chatbot::where('id', $id)->firstOrFail();

        // Get website data sources for the chatbot
        $dataSources = $bot->getWebsiteDataSources()->get();
        $pdfDataSources = $bot->getPdfFilesDataSources()->get();

        return view('widgets.data-sources-updates', [
            'dataSources' => $dataSources,
            'pdfDataSources' => $pdfDataSources,
        ]);
    }

    /**
     * Display the theme settings page for a chatbot.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function themeSettings($id)
    {
        // Find the chatbot by ID
        $bot = Chatbot::where('id', $id)->firstOrFail();

        return view('settings-theme', [
            'bot' => $bot,
        ]);
    }
}
