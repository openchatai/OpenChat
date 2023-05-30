<?php

namespace App\Http\Controllers;

use App\Http\Events\PdfDataSourceWasAdded;
use App\Http\Requests\UploadPdfFilesRequest;
use App\Http\Services\HandlePdfDataSource;
use App\Models\Chatbot;

class PdfDataSourceController extends Controller
{
    public function create(UploadPdfFilesRequest $request, $id)
    {
        /** @var Chatbot $bot */
        $bot = Chatbot::where('id', $id)->firstOrFail();
        $files = $request->file('pdffiles');
        $dataSource = (new HandlePdfDataSource($bot, $files))->handle();
        event(new PdfDataSourceWasAdded($bot->getId(), $dataSource->getId()));

        return redirect()->route('chatbot.settings-data', ['id' => $bot->getId()])->with('success', 'Your files have been uploaded successfully, we are training the model now, it should take around 5 minutes to reflect.');
    }

    public function show($id)
    {
        /** @var Chatbot $bot */
        $bot = Chatbot::where('id', $id)->firstOrFail();
        $pdfDataSources = $bot->getPdfFilesDataSources()->get();
        return view('onboarding.other-data-sources-pdf', ['bot' => $bot, 'pdfDataSources' => $pdfDataSources]);
    }

}
