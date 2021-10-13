<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AccessTokenManager;
use HappyrMatch\ApiClient\ApiClient;
use HappyrMatch\ApiClient\Model\Find\FindType;
use HappyrMatch\ApiClient\Model\Test\TestType;

class GetFindTypes
{
    public function run($url)
    {
        $apiClient = ApiClient::create(getenv('MATCH_BASE_URL'), getenv('MATCH_CLIENT_IDENTIFIER'), getenv('MATCH_CLIENT_SECRET'));

        $token = AccessTokenManager::fetch();

        $apiClient->authenticate(json_encode($token));
        try {
            $findTypes = $apiClient->find()->getTypes();
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
        $newToken = $apiClient->getAccessToken();
        if (null !== $newToken) {
            AccessTokenManager::store(json_decode($newToken, true));
        }

        echo '<h3>Got roles</h3>';
        /** @var FindType $type */
        foreach ($findTypes as $type) {
            echo $type->getId().': '.$type->getName().'<br>';
        }

        echo '<br><a href="/dashboard">Back to Dashboard</a>';
    }
}
