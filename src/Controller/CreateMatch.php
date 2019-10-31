<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use App\Service\Database;
use Symfony\Component\HttpClient\HttpClient;

class CreateMatch
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

        $candidateId = Database::findCandidate();
        $role = Database::findRole();

        if (empty($candidateId)) {
            echo 'Cannot get match without a candidate';

            return;
        }

        if (empty($role)) {
            echo 'Cannot get match without a role';

            return;
        }

        $response = $httpClient->request('GET', '/api/candidates/'.$candidateId.'/match?type=medium&role='.$role, [
            'json' => [
                'role' => Database::findRole(),
                'types' => ['ca2cfc8b-f2f9-4d5b-a293-925622f63ebb'],
                'redirect_uri' => 'http://'.$_SERVER['HTTP_HOST'].'/candidate-return',
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            echo 'Error when getting match:';
            echo '<br><br><code>'.$response->getContent(false).'</code><br><br>';

            echo '<a href="/">Back to Startpage</a>';

            return;
        }

        Database::storeMatch($response->toArray());

        echo 'We got a match<br>';
        echo '<a href="/dashboard">Back to Dashboard</a>';
    }
}
