<?php

namespace Rumenx\Feed;

/**
 * Simple response adapter that just returns the content as a string.
 * Used for plain PHP applications where no framework response is needed.
 */
class SimpleResponseAdapter implements FeedResponseInterface
{
    /**
     * Create a response instance - for plain PHP just return the content.
     *
     * @param mixed $content
     * @param int $status
     * @param array<string, string> $headers
     * @return mixed
     */
    public function make(mixed $content, int $status = 200, array $headers = []): mixed
    {
        return $content;
    }
}
