<?php

declare(strict_types=1);

namespace App\Service;

class AccessTokenManager
{
    private const STORAGE = '/var/cache/access_token.json';

    public static function store(array $accessToken): void
    {
        $file = self::getStoragePath();
        file_put_contents($file, json_encode($accessToken, JSON_PRETTY_PRINT));
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

    public static function hasToken(): bool
    {
        return file_exists(self::getStoragePath());
    }

    private static function getStoragePath()
    {
        return dirname(__DIR__).'/..'.self::STORAGE;
    }
}