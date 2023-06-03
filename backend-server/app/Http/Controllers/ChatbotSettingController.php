<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ChatbotSettingController extends Controller
{
    /**
     * Display the general settings page for a chatbot.
     *
     * @param  string  $id
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

    /**
     * Update the general settings for a chatbot.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
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
        $bot->save();

        return redirect()->route('chatbot.settings', ['id' => $bot->getId()])->with('success', 'Settings updated!');
    }

    /**
     * Display the data settings page for a chatbot.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\View\View
     */
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

        return view('widgets.data-sources-updates', [
            'dataSources' => $dataSources,
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
