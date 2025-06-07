<?php
namespace Rumenx\Feed\Laravel;

use Illuminate\Contracts\Routing\ResponseFactory;
use Rumenx\Feed\FeedResponseInterface;

class LaravelResponseAdapter implements FeedResponseInterface {
    public function __construct(private ResponseFactory $response) {}
    public function make(mixed $content, int $status = 200, array $headers = []): mixed { return $this->response->make($content, $status, $headers); }
}
