<?php

namespace App\Http;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

trait GetLogoFromUrlTrait
{
    public static function getLogo($url): ?string
    {
        try {
            // Extract domain name from URL
            $domain = parse_url($url, PHP_URL_HOST);

            // Make request to Clearbit API using Guzzle client
            $client = new Client();
            $response = $client->get('https://logo.clearbit.com/' . $domain);

            // Check if request was successful
            if ($response->getStatusCode() == 200) {
                // Generate hashed name for logo file
                $logoName = md5($domain) . '.png';

                // Store logo file using Laravel's Storage
                Storage::disk('public')->put($logoName, $response->getBody());

                // Return logo file name
                return $logoName;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }
}
