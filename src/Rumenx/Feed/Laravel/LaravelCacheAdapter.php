<?php
namespace Rumenx\Feed\Laravel;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Rumenx\Feed\FeedCacheInterface;

class LaravelCacheAdapter implements FeedCacheInterface {
    public function __construct(private CacheRepository $cache) {}
    public function has(string $key): bool { return $this->cache->has($key); }
    public function get(string $key, mixed $default = null): mixed { return $this->cache->get($key, $default); }
    public function put(string $key, mixed $value, int $ttl): void { $this->cache->put($key, $value, $ttl); }
    public function forget(string $key): void { $this->cache->forget($key); }
}
