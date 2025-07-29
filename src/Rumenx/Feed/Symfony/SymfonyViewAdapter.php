<?php

namespace Rumenx\Feed\Symfony;

use Symfony\Component\Templating\EngineInterface;
use Rumenx\Feed\FeedViewInterface;

class SymfonyViewAdapter implements FeedViewInterface
{
    public function __construct(private EngineInterface $engine)
    {
    }
    public function make(string $view, array $data = []): mixed
    {
        return $this->engine->render($view, $data);
    }
}
