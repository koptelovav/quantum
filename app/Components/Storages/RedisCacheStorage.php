<?php

namespace App\Components\Storages;

use Illuminate\Support\Facades\Redis;

/**
 * Class RedisCacheStorage
 * @package App\Storages
 */
class RedisCacheStorage implements StorageInterface
{
    protected $ttl;

    /**
     * RedisCacheStorage constructor
     *
     * @param int $ttl Cache lifetime (Default 1 hour)
     */
    public function __construct(int $ttl = 3600)
    {
        $this->ttl = $ttl;
    }

    public function get(string $key): ?string
    {
        return Redis::get($key);
    }

    public function store(string $key, string $value): bool
    {
        return (bool)Redis::set($key, $value, 'EX', $this->ttl);
    }
}
