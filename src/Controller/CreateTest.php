<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use App\Service\Database;
use Symfony\Component\HttpClient\HttpClient;

class CreateTest
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

        $candidateRedirectUri = 'http://'.$_SERVER['HTTP_HOST'].'/candidate-return';
        $response = $httpClient->request('POST', '/api/tests', [
            'json' => [
                'role' => Database::findRole(),
                'types' => ['ca2cfc8b-f2f9-4d5b-a293-925622f63ebb'],
                'redirect_uri' => $candidateRedirectUri,
            ],
        ]);

        if (201 !== $response->getStatusCode()) {
            echo 'Error when creating test:';
            echo '<br><br><code>'.$response->getContent(false).'</code><br><br>';

            echo '<a href="/">Back to Startpage</a>';

            return;
        }

        $data = $response->toArray();

        Database::storeTest([
            'id' => $data['data']['id'],
            'url' => $data['data']['attributes']['url'].'?redirect-uri='.urlencode($candidateRedirectUri),
        ]);

        echo 'Test is created<br>';
        echo '<a href="/dashboard">Back to Dashboard</a>';
    }
}
