<?php

namespace App\Http\Listeners;

use App\Http\Enums\WebsiteDataSourceStatusType;
use App\Http\Events\WebsiteDataSourceCrawlingWasCompleted;
use App\Http\Events\WebsiteDataSourceWasAdded;
use App\Models\CrawledPages;
use App\Models\WebsiteDataSource;
use DOMDocument;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Throwable;

class StartRecursiveCrawler implements ShouldQueue
{
    public function handle($event)
    {
        if (!$event instanceof WebsiteDataSourceWasAdded) {
            return;
        }

        /** @var WebsiteDataSource $dataSource */
        $dataSource = WebsiteDataSource::find($event->getWebsiteDataSourceId());
        $chatbotId = $event->getChatbotId();

        if ($dataSource->getCrawlingStatus()->isCompleted()) {
            return;
        }

        try {
            $rootUrl = $dataSource->getRootUrl();
            $url = $rootUrl;

            // Initialize an empty array to store the crawled URLs
            $crawledUrls = [];

            // Set the crawling status to "in progress"
            $dataSource->setCrawlingStatus(WebsiteDataSourceStatusType::IN_PROGRESS);
            $dataSource->save();

            // Start crawling from the root URL
            $this->crawl($url, $crawledUrls, 15, $rootUrl, $chatbotId, $dataSource->getId());

            event(new WebsiteDataSourceCrawlingWasCompleted($chatbotId, $dataSource->getId()));
        } catch (Exception|Throwable $exception) {
            $dataSource->setCrawlingStatus(WebsiteDataSourceStatusType::FAILED);
            $dataSource->save();
        }

        event(new WebsiteDataSourceCrawlingWasCompleted($chatbotId, $dataSource->getId()));
    }

    private function storeOnLocalDesk($htmlPage, $fileName, $folderName)
    {
        $path = $folderName . '/' . $fileName;
        Storage::disk('shared_volume')->put($path, $htmlPage);
    }

    private function crawl($url, &$crawledUrls, $maxPages, $rootUrl, $chatbotId, $dataSourceId): void
    {
        // Check if the maximum page limit has been reached
        if (count($crawledUrls) >= $maxPages) {
            return;
        }

        // Check if the URL has already been crawled
        if (in_array($url, $crawledUrls)) {
            return;
        }

        // Add the current URL to the crawled URLs list
        $crawledUrls[] = $url;

        try {
            // Send an HTTP GET request to the URL
            $client = new Client();
            $response = $client->get($url);

            // Retrieve the HTML content of the page
            $html = $response->getBody();

            // Store the crawled page content in the database
            $this->storeCrawledPageContentToDatabase($url, $response, $chatbotId, $dataSourceId, $html);

            // Extract all the links from the HTML content
            $links = $this->extractLinks($html, $rootUrl);

            // Recursively crawl each extracted link
            foreach ($links as $link) {
                // Crawl
                $this->crawl($link, $crawledUrls, $maxPages, $rootUrl, $chatbotId, $dataSourceId);

                // Update crawling progress
                $progress = $this->calculateCrawlingProgress(count($crawledUrls), $maxPages);
                $this->updateCrawlingProgress($chatbotId, $dataSourceId, $progress);
            }
        } catch (Exception|GuzzleException|Throwable $e) {
            return;
            // Ignore the exception and continue crawling other links
        }
    }

    public function storeCrawledPageContentToDatabase(string $url, ResponseInterface $response, UuidInterface $chatbotId, UuidInterface $dataSourceId, ?string $html): void
    {
        $textPath = $dataSourceId . "/" . Str::random() . ".txt";
        $normalizedText = $this->getNormalizedContent($response->getBody());
        $this->storeOnLocalDesk($normalizedText, $textPath, $dataSourceId);

        $page = new CrawledPages();
        $page->setUrl($url);
        $page->setStatusCode($response->getStatusCode());
        $page->setChatbotId($chatbotId);
        $page->setTitle($this->getCrawledPageTitle($response->getBody()));
        $page->setId(Uuid::uuid4());
        $page->setWebsiteDataSourceId($dataSourceId);
        $page->save();
    }

    private function getNormalizedContent($html): string
    {
        // Remove inline script and style tags and their contents
        $html = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $html);
        $html = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/i', '', $html);

        // Remove all HTML tags except for line break and paragraph tags
        $html = strip_tags($html, '<br><p>');

        // Replace line breaks and paragraphs with new lines
        $html = preg_replace('/<(br|p)[^>]*>/i', "\n", $html);

        // Remove extra whitespace and normalize new lines
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/\n\s*\n/', "\n", $html);

        // Trim leading and trailing whitespace
        return trim($html);
    }

    private function getCrawledPageTitle(string $html): ?string
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Disable error reporting for invalid HTML
        @$dom->loadHTML($html);
        libxml_clear_errors();

        $titleElements = $dom->getElementsByTagName('title');
        if ($titleElements->length > 0) {
            $title = $titleElements->item(0)->textContent;
            $title = trim($title);

            // Decode any HTML entities in the title
            return html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return null; // Return null if no title element was found
    }

    private function extractLinks($html, $rootUrl): array
    {
        // Use regular expressions or an HTML parsing library
        // to extract the URLs from the HTML content.
        // Here's a simple example using regular expressions:

        $pattern = '/<a\s(?:[^>]*)href="([^"]*)"/i';
        preg_match_all($pattern, $html, $matches);

        // Extract the URLs from the matches
        $urls = $matches[1];

        $links = [];

        foreach ($urls as $url) {
            if (str_starts_with($url, 'http')) {
                // Full URL starting with "http"
                $links[] = $url;
            } elseif (str_starts_with($url, '/')) {
                // URL starting with "/"
                $parsedRoot = parse_url($rootUrl);
                $rootHost = $parsedRoot['scheme'] . '://' . $parsedRoot['host'];
                $links[] = $rootHost . $url;
            }
        }

        // Remove any duplicate URLs
        $links = array_unique($links);

        // Remove any URL that does not belong to the same root URL host
        return array_filter($links, function ($url) use ($rootUrl) {
            $urlHost = $this->removeWwwPrefix(parse_url($url, PHP_URL_HOST));
            $rootHost = $this->removeWwwPrefix(parse_url($rootUrl, PHP_URL_HOST));
            return $urlHost === $rootHost;
        });
    }


    private function removeWwwPrefix($host): array|string|null
    {
        return preg_replace('/^www\./', '', $host);
    }

    public function calculateCrawlingProgress($crawledPages, $maxPages)
    {
        if ($maxPages <= 0) {
            return 0; // Avoid division by zero
        }

        $progress = ($crawledPages / $maxPages) * 100;
        // Cap the progress at 100%

        return min($progress, 100);
    }

    public function updateCrawlingProgress($chatbotId, $dataSourceId, $progress): void
    {
        /** @var WebsiteDataSource $dataSource */
        $dataSource = WebsiteDataSource::find($dataSourceId);
        $dataSource->setCrawlingProgress($progress);
        $dataSource->save();
    }
}
