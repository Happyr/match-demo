<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use App\Service\Database;
use Symfony\Component\HttpClient\HttpClient;

class DownloadRoleCategory
{
    public function run($url)
    {
        $httpClient = HttpClient::create([
            'base_uri' => getenv('MATCH_BASE_URL'),
            'headers' => [
                'Accept' => 'application/vnd.api+json',
            ],
        ]);

        $response = $httpClient->request('GET', '/api/roles/all-categories', [
            'auth_bearer' => AccessTokenManager::fetch()['access_token'] ?? '',
            'headers' => [
                'Content-Type' => 'application/vnd.api+json',
            ]
        ]);

        if (200 !== $response->getStatusCode()) {
            echo 'Error when creating role:';
            echo '<br><br><code>'.$response->getContent(false).'</code><br><br>';

            echo '<a href="/">Back to Startpage</a>';

            return;
        }

        echo '<a href="/dashboard">Back to Dashboard</a><br>';
        echo 'Here they are:<br><br>';

        $data = $response->toArray()['data'];
        echo '<pre>'.json_encode($data, JSON_PRETTY_PRINT).'</pre>';
    }
}
