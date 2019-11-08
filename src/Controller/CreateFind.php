<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use App\Service\Database;
use Symfony\Component\HttpClient\HttpClient;

class CreateFind
{
    public function run($url)
    {
        $httpClient = HttpClient::create([
            'base_uri' => getenv('MATCH_BASE_URL'),
            'auth_bearer' => AccessTokenManager::fetch()['access_token'] ?? '',
            'headers' => [
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
            ],
        ]);

        $response = $httpClient->request('POST', '/api/find', [
            'json' => [
                'role' => Database::findRole(),
                'type' => '585e6742-c187-4ee3-adea-947cae9e0acf',
                'callback_url' => 'https://example.com/where-to-send-candidates',
            ],
        ]);

        if (202 !== $response->getStatusCode()) {
            echo 'Error when creating Find:';
            echo '<br><br><code>'.$response->getContent(false).'</code><br><br>';

            echo '<a href="/">Back to Startpage</a>';

            return;
        }

        echo 'Find request was accepted<br>';
        echo '<a href="/dashboard">Back to Dashboard</a>';
    }
}
