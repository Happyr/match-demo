<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use Symfony\Component\HttpClient\HttpClient;

/**
 * If we want to refresh tha access token
 */
class AuthenticationRefresh
{
    public function run($url)
    {
        $accessToken = AccessTokenManager::fetch();

        $httpClient = HttpClient::create([
            'base_uri' => getenv('MATCH_BASE_URL'),
            'auth_bearer' => $accessToken['access_token'] ?? '',
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        $response = $httpClient->request('POST', '/oauth/token', [
            'body' => http_build_query([
                'refresh_token' => $accessToken['refresh_token'] ?? '',
                'grant_type' => 'refresh_token',
                'client_id' => getenv('MATCH_CLIENT_IDENTIFIER'),
                'client_secret' => getenv('MATCH_CLIENT_SECRET'),
            ]),
        ]);

        if (200 !== $response->getStatusCode()) {
            echo 'Error when the new getting access code:';
            echo '<br><br><code>'.$response->getContent(false).'</code><br><br>';

            echo '<a href="/">Back to Startpage</a>';

            return;
        }

        AccessTokenManager::store($response->toArray());

        echo 'We saved the new access token to disk. Go to next step<br><br>';
        echo '<a href="/dashboard">Continue</a>';
    }
}
