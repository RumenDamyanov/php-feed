<?php

// FeedResponseInterface.php
// Interface for response adapter used by Feed.

namespace Rumenx\Feed;

/**
 * Interface for response adapter used by Feed.
 */
interface FeedResponseInterface
{
    /**
     * Create a response instance.
     * @param mixed $content
     * @param int $status
     * @param array<string, string> $headers
     * @return mixed
     */
    public function make(mixed $content, int $status = 200, array $headers = []): mixed;
}
