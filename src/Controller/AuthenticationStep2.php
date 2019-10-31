<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use Symfony\Component\HttpClient\HttpClient;

/**
 * This is the page we return to when user is authenticated.
 */
class AuthenticationStep2
{

    public function run($url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $query);

        $httpClient = HttpClient::create([
            'base_uri' => getenv('MATCH_BASE_URL')
        ]);

        $response = $httpClient->request('POST', '/oauth/token', [
            'body'=> http_build_query([
                'code' => $query['code'],
                'grant_type' => 'authorization_code',
                'client_id' => getenv('MATCH_CLIENT_IDENTIFIER'),
                'client_secret' => getenv('MATCH_CLIENT_SECRET'),
                'redirect_uri' => getenv('MATCH_AUTH_REDIRECT_URI'),
            ])
        ]);

        if ($response->getStatusCode() !== 200) {
            echo 'Error when getting access code:';
            echo '<br><br><code>'.$response->getContent(false).'</code><br><br>';

            echo '<a href="/">Back to Startpage</a>';
            return;
        }

        AccessTokenManager::store($response->toArray());

        echo "We saved the access token to disk. Go to next step<br><br>";
        echo '<a href="/dashboard">Continue</a>';
    }
}
