<?php
// SymfonyViewAdapterTest.php
// Unit tests for the SymfonyViewAdapter.

/**
 * @covers \Rumenx\Feed\Symfony\SymfonyViewAdapter
 *
 * These tests verify that the SymfonyViewAdapter correctly delegates view rendering to Symfony's templating engine.
 */

use Rumenx\Feed\Symfony\SymfonyViewAdapter;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

describe('SymfonyViewAdapter', function () {
    it('renders a view', function () {
        // Skip this test if symfony/templating is deprecated or not installed
        if (!interface_exists(\Symfony\Component\Templating\EngineInterface::class)) {
            test()->markTestSkipped('symfony/templating is not available or deprecated.');
        }
        $engine = new class implements \Symfony\Component\Templating\EngineInterface {
            public function render(string|\Symfony\Component\Templating\TemplateReferenceInterface $name, array $parameters = []): string { return $name . json_encode($parameters); }
            public function exists(string|\Symfony\Component\Templating\TemplateReferenceInterface $name): bool { return true; }
            public function supports(string|\Symfony\Component\Templating\TemplateReferenceInterface $name): bool { return true; }
        };
        $adapter = new \Rumenx\Feed\Symfony\SymfonyViewAdapter($engine);
        $result = $adapter->make('foo', ['bar' => 'baz']);
        expect($result)->toBe('foo{"bar":"baz"}');
    });
});
