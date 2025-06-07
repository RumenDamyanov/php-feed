<?php
// FeedCacheInterface.php
// Interface for cache adapter used by Feed.

namespace Rumenx\Feed;

/**
 * Interface for cache adapter used by Feed.
 */
interface FeedCacheInterface {
    /**
     * Check if a cache key exists.
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get a value from cache.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Put a value in cache.
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return void
     */
    public function put(string $key, mixed $value, int $ttl): void;

    /**
     * Remove a value from cache.
     * @param string $key
     * @return void
     */
    public function forget(string $key): void;
}
