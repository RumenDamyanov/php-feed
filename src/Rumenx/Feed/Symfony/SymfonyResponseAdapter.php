<?php
namespace Rumenx\Feed\Symfony;

use Symfony\Component\HttpFoundation\Response;
use Rumenx\Feed\FeedResponseInterface;

class SymfonyResponseAdapter implements FeedResponseInterface
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function setStatusCode($status)
    {
        $this->response->setStatusCode($status);
    }

    public function getContent()
    {
        return $this->response->getContent();
    }

    public function setContent($content)
    {
        $this->response->setContent($content);
    }

    public function make(mixed $content, int $status = 200, array $headers = []): mixed {
        return new Response($content, $status, $headers);
    }

    // ...other methods from SymfonyResponseAdapter.php...
}
