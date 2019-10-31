<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;

/**
 * This is the page where we start the authentication
 */
class Step1
{

    public function run($url)
    {
        $html = <<<HTML
<h1>Match demo</h1>
<p>The HTML is very limited and the application is poor. We are using minimal code just to demo stuff.</p>
HTML;
        echo $html;

        $authUrl = getenv('MATCH_BASE_URL').'/oauth/authorize?'.http_build_query([
            'response_type' => 'code',
            'client_id' => getenv('MATCH_CLIENT_IDENTIFIER'),
            'redirect_uri' => getenv('MATCH_AUTH_REDIRECT_URI'),
            'scope' => 'find add_candidate test match learn',
        ]);

        echo '<a href="'.$authUrl.'">Authenticate</a><br><br>';

        // TODO if we got an access token already, we can skip authentication
        if (AccessTokenManager::hasToken()) {
            echo '<p>You already have a token stored. It might still be valid, do you want to try?</p>';
            echo '<a href="/dashboard">Continue with existing token</a>';
        }
    }
}
