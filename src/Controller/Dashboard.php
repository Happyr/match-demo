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

<h3>Role</h3>
<p>Current role id: {$role}</p>
<a href="/create-role">Create new role</a>

<h3>Test</h3>
<p>Current test id: {$test['id']}</p>
<a href="/create-role">Create new test</a>
HTML;
        if (!empty($test['url'])) {
            echo '<p>Send a candidate to this URL to ask them to "do the test": </p>'.$test['url'];
        }

        echo <<<HTML
<h3>Candidate</h3>
<p>Current candidate id: {$candidate}</p>
<p>Here is the match of that candidate:</p>
HTML;

        if (empty($match)) {
            echo '<a href="/get-match">Calculate match</a>';
        } else {
            echo '<pre>'.json_encode($match, JSON_PRETTY_PRINT).'</pre>';
        }
    }
}
