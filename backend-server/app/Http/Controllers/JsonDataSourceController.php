<?php

namespace App\Http\Controllers;

use App\Http\Events\JsonDataSourceWasAdded;
use App\Http\Requests\UploadJsonFilesRequest;
use App\Http\Services\HandleJsonDataSource;
use App\Models\Chatbot;

class JsonDataSourceController extends Controller
{
    public function create(UploadJsonFilesRequest $request, $id)
    {
        /** @var Chatbot $bot */
        $bot = Chatbot::where('id', $id)->firstOrFail();
        $files = $request->file('jsonfiles');
        $dataSource = (new HandleJsonDataSource($bot, $files))->handle();
        event(new JsonDataSourceWasAdded($bot->getId(), $dataSource->getId()));

        return redirect()->route('chatbot.settings-data', ['id' => $bot->getId()])->with('success', 'Your files have been uploaded successfully, we are training the model now, it should take around 5 minutes to reflect.');
    }

    public function show($id)
    {
        /** @var Chatbot $bot */
        $bot = Chatbot::where('id', $id)->firstOrFail();
        $jsonDataSources = $bot->getJsonFilesDataSources()->get();
        return view('onboarding.other-data-sources-json', ['bot' => $bot, 'jsonDataSources' => $jsonDataSources]);
    }

}
