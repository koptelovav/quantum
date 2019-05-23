<?php

namespace App\Components\Storages;

use Illuminate\Support\Facades\Redis;

/**
 * Class RedisCacheStorage
 * @package App\Storages
 */
class RedisCacheStorage implements StorageInterface
{
    public function get(string $key): ?string
    {
        return Redis::get($key);
    }

    public function store(string $key, string $value): bool
    {
        return (bool)Redis::set($key, $value);
    }
}
