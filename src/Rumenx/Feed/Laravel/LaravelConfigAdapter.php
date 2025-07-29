<?php

namespace Rumenx\Feed\Laravel;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Rumenx\Feed\FeedConfigInterface;

class LaravelConfigAdapter implements FeedConfigInterface
{
    public function __construct(private ConfigRepository $config)
    {
    }
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config->get($key, $default);
    }
}
