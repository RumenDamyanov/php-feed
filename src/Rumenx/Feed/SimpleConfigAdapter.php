<?php

namespace Rumenx\Feed;

/**
 * Simple config adapter that provides basic default configuration values.
 * Used for plain PHP applications where no framework config is available.
 */
class SimpleConfigAdapter implements FeedConfigInterface
{
    /** @var array<string, mixed> */
    private array $config;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'application.language' => 'en',
            'application.url' => 'http://localhost',
        ], $config);
    }

    /**
     * Get configuration value with optional default.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }
}
