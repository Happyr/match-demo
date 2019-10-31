<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Database;

/**
 * This controller is used when a candidate returns form a test.
 *
 */
class CandidateReturn
{
    public function run($url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $query);

        Database::storeCandidate($query['candidate-id']);

        echo "Welcome back from doing the test. We saved the candidate id";
        echo '<a href="/dashboard">Back to Dashboard</a>';
    }
}
