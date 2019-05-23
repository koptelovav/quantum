<?php

namespace App\Components\Storages;

/**
 * Interface StorageInterface
 * @package App\Storages
 */
interface StorageInterface
{
    /**
     * Get cached value by key
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Save value to cache
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function store(string $key, string $value): bool;
}
