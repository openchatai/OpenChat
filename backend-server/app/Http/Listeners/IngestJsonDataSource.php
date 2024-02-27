<?php

namespace App\Http\Listeners;

use App\Http\Events\JsonDataSourceWasAdded;
use App\Models\JsonDataSource;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;

class IngestJsonDataSource implements ShouldQueue
{

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle($event)
    {
        if (!$event instanceof JsonDataSourceWasAdded) {
            return;
        }

        $botId = $event->getChatbotId();
        $jsonDataSourceId = $event->getJsonDataSourceId();

        /** @var JsonDataSource $jsonDataSource */
        $jsonDataSource = JsonDataSource::where('id', $jsonDataSourceId)->firstOrFail();
        $files = $jsonDataSource->getFiles();

        $requestBody = [
            'type' => 'json',
            'shared_folder' => $jsonDataSource->getFolderName(),
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
