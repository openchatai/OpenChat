<?php

namespace App\Http\Listeners;

use App\Http\Enums\IngestStatusType;
use App\Http\Events\CodebaseDataSourceWasAdded;
use App\Models\CodebaseDataSource;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;

class IngestCodebaseDataSource implements ShouldQueue
{

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle($event)
    {
        if (!$event instanceof CodebaseDataSourceWasAdded) {
            return;
        }

        $botId = $event->getChatbotId();
        $codebaseDataSourceId = $event->getCodebaseDataSourceId();


        /** @var CodebaseDataSource $datasource */
        $datasource = CodebaseDataSource::find($codebaseDataSourceId);


        $repo = $datasource->getRepository();

        $requestBody = [
            'type' => 'codebase',
            'repo' => $repo,
            'namespace' => $botId,
        ];

        // Call to ingest service endpoint
        $client = new Client();
        $response = $client->request('POST', 'http://llm-server:3000/api/ingest', [
            'json' => $requestBody,
        ]);

        $datasource->setIngestedAt(now());

        if ($response->getStatusCode() !== 200) {
            $datasource->setIngestionStatus(IngestStatusType::FAILED);
        } else {
            $datasource->setIngestionStatus(IngestStatusType::SUCCESS);
        }

        $datasource->save();
    }
}
