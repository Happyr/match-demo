<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use Symfony\Component\HttpClient\HttpClient;

class GetTestTypes
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

        $response = $httpClient->request('GET', '/api/test-types');

        if (200 !== $response->getStatusCode()) {
            echo 'Error when getting test types:';
            echo '<br><br><code>'.$response->getContent(false).'</code><br><br>';

            echo '<a href="/">Back to Startpage</a>';

            return;
        }

        echo '<h3>Got test types</h3>';

        $data = $response->toArray()['data'];
        echo '<pre>'.json_encode($data, JSON_PRETTY_PRINT).'</pre>';

        echo '<br><a href="/dashboard">Back to Dashboard</a>';
    }
}
