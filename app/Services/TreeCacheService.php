<?php

namespace App\Services;

use App\Components\TreeBuilder\TreeBuilderInterface;
use App\Components\Storages\StorageInterface;
use Illuminate\Session\Store as SessionStore;

/**
 * Class TreeCacheService
 * @package App\Managers
 */
class TreeCacheService
{
    protected $session;
    protected $storage;
    protected $cache;

    public function __construct(StorageInterface $storage, SessionStore $session)
    {
        $this->session = $session;
        $this->storage = $storage;
    }

    public function getFromCache(): TreeBuilderInterface
    {
        $stored = $this->storage->get($this->getCacheKey());
        return $stored ? \unserialize($stored) : app()->make(TreeBuilderInterface::class);
    }

    public function saveToCache(TreeBuilderInterface $cache): self
    {
        $this->storage->store($this->getCacheKey(), serialize($cache));

        return $this;
    }

    /**
     * Get unique cache key for user
     * @return string
     */
    protected function getCacheKey(): string
    {
        return $this->session->getId() . self::class;
    }
}
