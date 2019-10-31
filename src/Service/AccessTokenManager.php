<?php

declare(strict_types=1);

namespace App\Service;

class AccessTokenManager
{
    private const STORAGE = '/var/cache/access_token.json';

    public static function store(array $accessToken): void
    {
        file_put_contents(self::getStoragePath(), json_encode($accessToken, JSON_PRETTY_PRINT));
    }

    public static function get(): array
    {
        $file = self::getStoragePath();
        if (!file_exists($file)) {
            throw new \RuntimeException('No access token found');
        }

        $fileContent = file_get_contents($file);

        return json_decode($fileContent, true);
    }

    private static function getStoragePath()
    {
        return realpath(dirname(__DIR__).'/..'.self::STORAGE);
    }
}