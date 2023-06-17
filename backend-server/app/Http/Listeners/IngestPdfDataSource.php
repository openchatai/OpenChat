<?php

namespace App\Http\Listeners;

use App\Http\Enums\IngestStatusType;
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

        try {
            /** @var PdfDataSource $pdfDataSource */
            $pdfDataSource = PdfDataSource::where('id', $pdfDataSourceId)->firstOrFail();

            $requestBody = [
                'type' => 'pdf',
                'shared_folder' => $pdfDataSource->getFolderName(),
                'namespace' => $botId,
            ];

            // Call to ingest service endpoint
            $client = new Client();
            $response = $client->request('POST', "http://llm-server:3000/api/ingest", [
                'json' => $requestBody,
                'timeout' => 200,
            ]);

            if ($response->getStatusCode() !== 200) {
                $pdfDataSource->setStatus(IngestStatusType::FAILED);
                $pdfDataSource->save();
                return;
            }

            $pdfDataSource->setStatus(IngestStatusType::SUCCESS);
            $pdfDataSource->save();

        } catch (Exception $e) {
            $pdfDataSource->setStatus(IngestStatusType::FAILED);
            $pdfDataSource->save();
            return;
        }
    }
}
