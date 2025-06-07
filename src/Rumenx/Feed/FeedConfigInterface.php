<?php
// FeedConfigInterface.php
// Interface for config adapter used by Feed.

namespace Rumenx\Feed;

/**
 * Interface for config adapter used by Feed.
 */
interface FeedConfigInterface {
    /**
     * Get a config value by key.
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;
}
