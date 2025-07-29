<?php

namespace Rumenx\Feed;

/**
 * Simple cache adapter that provides in-memory caching for the current request.
 * Used for plain PHP applications where no persistent cache is needed.
 */
class SimpleCacheAdapter implements FeedCacheInterface
{
    /** @var array<string, mixed> */
    private array $cache = [];

    /**
     * Check if an item exists in the cache.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    /**
     * Get an item from the cache.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache[$key] ?? $default;
    }

    /**
     * Put an item in the cache.
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl Time to live in minutes (ignored in simple implementation)
     * @return void
     */
    public function put(string $key, mixed $value, int $ttl): void
    {
        $this->cache[$key] = $value;
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     * @return void
     */
    public function forget(string $key): void
    {
        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
        }
    }
}
