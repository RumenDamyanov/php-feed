<?php

namespace Rumenx\Feed;

/**
 * Factory class for creating Feed instances with simple adapters.
 * Provides an easy way to create feeds for plain PHP usage.
 */
class FeedFactory
{
    /**
     * Create a Feed instance with simple adapters for plain PHP usage.
     *
     * @param array<string, mixed> $config Optional configuration
     * @return Feed
     */
    public static function create(array $config = []): Feed
    {
        return new Feed([
            'cache' => new SimpleCacheAdapter(),
            'config' => new SimpleConfigAdapter($config),
            'response' => new SimpleResponseAdapter(),
            'view' => new SimpleViewAdapter(),
        ]);
    }
}
