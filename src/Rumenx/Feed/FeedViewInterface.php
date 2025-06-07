<?php
// FeedViewInterface.php
// Interface for view adapter used by Feed.

namespace Rumenx\Feed;

/**
 * Interface for view adapter used by Feed.
 */
interface FeedViewInterface {
    /**
     * Create a view instance.
     * @param string $view
     * @param array $data
     * @return mixed
     */
    public function make(string $view, array $data = []): mixed;
}
