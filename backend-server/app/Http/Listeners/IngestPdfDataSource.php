<?php

namespace App\Http\Listeners;

use App\Http\Events\PdfDataSourceWasAdded;
use App\Models\PdfDataSource;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;

class IngestPdfDataSource implements ShouldQueue
{

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle($event)
    {
        if (!$event instanceof PdfDataSourceWasAdded) {
            return;
        }

        $botId = $event->getChatbotId();
        $pdfDataSourceId = $event->getPdfDataSourceId();

        /** @var PdfDataSource $pdfDataSource */
        $pdfDataSource = PdfDataSource::where('id', $pdfDataSourceId)->firstOrFail();
        $files = $pdfDataSource->getFiles();

        $requestBody = [
            'type' => 'pdf',
            'shared_folder' => $pdfDataSource->getFolderName(),
            'namespace' => $botId,
        ];

        // Call to ingest service endpoint
        $client = new Client();
        $response = $client->request('POST', "http://llm-server:3000/api/ingest", [
            'json' => $requestBody,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Ingest service returned an error: ' . $response->getBody()->getContents());
        }
    }
}
