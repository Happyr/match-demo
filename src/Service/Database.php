<?php

declare(strict_types=1);

namespace App\Service;

/**
 * A very simple database implementation.
 */
class Database
{
    private const STORAGE = '/var/cache/database.json';

    public static function findRole(): ?string
    {
        return self::fetch()['role'] ?? null;
    }

    public static function storeRole(string $id): void
    {
        self::store('role', $id);
    }

    public static function findCandidate(): ?string
    {
        return self::fetch()['candidate'] ?? null;
    }

    public static function storeCandidate(string $id): void
    {
        self::store('candidate', $id);
    }

    public static function findTest(): array
    {
        return self::fetch()['test'] ?? ['id' => null];
    }

    public static function storeTest(array $test): void
    {
        self::store('test', $test);
    }

    public static function findMatch(?string $candidateId): array
    {
        if ($candidateId === null) {
            return [];
        }
        return self::fetch()['match'] ?? [];
    }

    public static function storeMatch(array $match): void
    {
        self::store('match', $match);
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
