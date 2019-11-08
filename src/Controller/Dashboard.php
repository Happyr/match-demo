<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Database;

/**
 * From here you may try different API calls.
 * We assume you have a valid access token.
 */
class Dashboard
{
    public function run($url)
    {
        $role = Database::findRole();
        $test = Database::findTest();
        $candidate = Database::findCandidate();
        $match = Database::findMatch($candidate);

        echo <<<HTML
<h1>Dashboard</h1>
<p>We assume that you have a valid access token. If you don't have that yet you will notice soon =)</p>

<h2>Role</h2>
<p>Current role id: {$role}</p>
<a href="/create-role">Create new role</a>

<h2>Test</h2>
<p>Current test id: {$test['id']}</p>
<a href="/create-test">Create new test</a>
HTML;
        if (!empty($test['url'])) {
            echo '<p>Send a candidate to this URL to ask them to "do the test": </p>'.$test['url'];
        }

        echo <<<HTML
<h2>Candidate</h2>
<p>Current candidate id: {$candidate}</p>
<p>Here is the match of that candidate:</p>
HTML;

        if (empty($match)) {
            echo '<a href="/get-match">Calculate match</a>';
        } else {
            echo '<pre>'.json_encode($match, JSON_PRETTY_PRINT).'</pre>';
        }

        echo <<<HTML
<h2>Find request</h2>
Send new Find request by <a href="/find">clicking here</a>.


<h2>Reresh AccessToken</h2>
If the token have expired, you may <a href="/refresh">refresh it</a>.


<h2>Startpage</h2>
<a href="/">Back to startpage</a>

HTML;
    }
}
