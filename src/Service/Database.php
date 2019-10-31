<?php

declare(strict_types=1);

namespace App\Service;

/**
 * A very simple database implementation.
 */
class Database
{
    private const STORAGE = '/var/cache/database.json';

    public static function findRole()
    {
        return self::fetch()['role'] ?? null;
    }

    public static function storeRole(string $id): void
    {
        self::store('role', $id);
    }

    public static function findTest()
    {
        return self::fetch()['test'] ?? null;
    }

    public static function storeTest(string $id): void
    {
        self::store('test', $id);
    }

    public static function findCandidate()
    {
        return self::fetch()['candidate'] ?? null;
    }

    public static function storeCandidate(string $id): void
    {
        self::store('candidate', $id);
    }

    private static function store(string $key, $value): void
    {
        $data = self::fetch();
        $data[$key] = $value;
        $file = self::getStoragePath();
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    }

    private static function fetch(): array
    {
        $file = self::getStoragePath();
        if (!file_exists($file)) {
            return [];
        }

        $fileContent = file_get_contents($file);

        return json_decode($fileContent, true);
    }

    private static function getStoragePath()
    {
        return dirname(__DIR__).'/..'.self::STORAGE;
    }
}
