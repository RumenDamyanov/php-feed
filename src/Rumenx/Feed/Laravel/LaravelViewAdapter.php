<?php

// LaravelViewAdapter.php
// Adapter for integrating FeedViewInterface with Laravel's ViewFactory.

namespace Rumenx\Feed\Laravel;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Rumenx\Feed\FeedViewInterface;

/**
 * LaravelViewAdapter bridges FeedViewInterface to Laravel's ViewFactory.
 *
 * @package Rumenx\Feed\Laravel
 */
class LaravelViewAdapter implements FeedViewInterface
{
    /**
     * @param ViewFactory $view Laravel's view factory instance
     */
    public function __construct(private ViewFactory $view)
    {
    }

    /**
     * Create a view instance.
     *
     * @param string $view The view name
     * @param array<string, mixed> $data Data to pass to the view
     * @return mixed
     */
    public function make(string $view, array $data = []): mixed
    {
        return $this->view->make($view, $data);
    }
}
