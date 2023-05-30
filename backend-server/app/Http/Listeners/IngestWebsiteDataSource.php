<?php

namespace App\Http\Listeners;

use App\Http\Events\PdfDataSourceWasAdded;
use App\Http\Events\WebsiteDataSourceCrawlingWasCompleted;
use App\Models\PdfDataSource;
use App\Models\WebsiteDataSource;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;

class IngestWebsiteDataSource implements ShouldQueue
{
    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle($event)
    {
        if (!$event instanceof WebsiteDataSourceCrawlingWasCompleted) {
            return;
        }

        $botId = $event->getChatbotId();
        $websiteDataSourceId = $event->getWebsiteDataSourceId();

        /** @var WebsiteDataSource $websiteDataSource */
        $websiteDataSource = WebsiteDataSource::find($websiteDataSourceId);

        $requestBody = [
            'type' => 'website',
            'shared_folder' => $websiteDataSourceId,
            'namespace' => $botId,
        ];

        // Call to ingest service endpoint
        $client = new Client();
        // @todo - this is not optimal, we should use .env
        $response = $client->request('POST', "http://llm-server:3000/api/ingest", ['json' => $requestBody,]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Ingest service returned an error: ' . $response->getBody()->getContents());
        }
    }
}
