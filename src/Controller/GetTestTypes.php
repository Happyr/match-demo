<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use HappyrMatch\ApiClient\ApiClient;
use HappyrMatch\ApiClient\Model\Test\TestType;

class GetTestTypes
{
    public function run($url)
    {
        $apiClient = ApiClient::create(getenv('MATCH_BASE_URL'), getenv('MATCH_CLIENT_IDENTIFIER'), getenv('MATCH_CLIENT_SECRET'));

        $token = AccessTokenManager::fetch();

        $apiClient->authenticate(json_encode($token));
        try {
            $testTypes = $apiClient->test()->getTypes();
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
        $newToken = $apiClient->getAccessToken();
        if (null !== $newToken) {
            AccessTokenManager::store(json_decode($newToken, true));
        }

        echo '<h3>Got test types</h3>';

        /** @var TestType $type */
        foreach ($testTypes as $type) {
            echo sprintf('%s: %s (%s)<br>', $type->getId(), $type->getName(), $type->getVisibility());
        }

        echo '<br><a href="/dashboard">Back to Dashboard</a>';
    }
}
