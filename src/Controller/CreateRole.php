<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use App\Service\Database;
use Symfony\Component\HttpClient\HttpClient;

class CreateRole
{
    public function run($url)
    {
        $httpClient = HttpClient::create([
            'base_uri' => getenv('MATCH_BASE_URL'),
            'headers' => [
                'Accept' => 'application/vnd.api+json',
            ],
        ]);

        // Get the role category
        $response = $httpClient->request('GET', 'https://api.happyrmatch.com/api/role-categories/search?language=sv&name=receptionist');
        $roleCategory = $response->toArray()['data'][0]['id'];

        $response = $httpClient->request('POST', '/api/roles', [
            'auth_bearer' => AccessTokenManager::fetch()['access_token'] ?? '',
            'headers' => [
                'Content-Type' => 'application/vnd.api+json',
            ],
            'json' => [
                'advert_title' => 'Funny receptionist',
                'advert_body_text' => 'Long text',
                'advert_body_html' => 'Long <b>text<\/b>',
                'advert_link' => 'https://my-app.com/advert/123',
                'description' => 'Short text',
                'employment_duration' => 1,
                'role_category' => $roleCategory,
                'work_hours' => 1,
                'location' => [
                    'country' => 'SE',
                    'region' => 'Stockholms län',
                    'city' => 'Stockholm',
                    'address' => 'Drottninggatan 7',
                ],
            ],
        ]);

        if (201 !== $response->getStatusCode()) {
            echo 'Error when creating role:';
            echo '<br><br><code>'.$response->getContent(false).'</code><br><br>';

            echo '<a href="/">Back to Startpage</a>';

            return;
        }

        Database::storeRole($response->toArray()['data']['id']);

        echo 'Role is created<br>';
        echo '<a href="/dashboard">Back to Dashboard</a>';
    }
}
