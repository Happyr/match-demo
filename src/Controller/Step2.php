<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use Symfony\Component\HttpClient\HttpClient;

/**
 * This is the page we return to when user is authenticated.
 */
class Step2
{

    public function run($url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $query);

        $httpClient = HttpClient::create([
            'base_uri' => getenv('MATCH_BASE_URL')
        ]);

        $response = $httpClient->request('POST', '/oauth/token', [
            'body'=> http_build_query([
                'grant_type' => 'authorization_code',
                'client_id' => getenv('MATCH_CLIENT_IDENTIFIER'),
                'client_secret' => getenv('MATCH_CLIENT_SECRET'),
                'code' => $query['code'],
                // The redirect_uri must match the URI configured in the API dashboard.
                'redirect_uri' => 'http://127.0.0.1:8000/step-2',
            ])
        ]);

        if ($response->getStatusCode() !== 200) {
            echo 'Error when getting access code:';
            echo '<pre>'.$response->getContent(false).'</pre>';
            return;
        }

        AccessTokenManager::store($response->toArray());

        echo "We saved the access token to disk. Go to next step<br><<br>";
        echo '<a href="/step-3">Continue</a>';
    }
}
