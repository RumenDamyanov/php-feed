<?php
// SymfonyResponseAdapterTest.php
// Unit tests for the SymfonyResponseAdapter.

/**
 * @covers \Rumenx\Feed\Symfony\SymfonyResponseAdapter
 *
 * These tests verify that the SymfonyResponseAdapter correctly wraps and creates Symfony responses.
 */

use Rumenx\Feed\Symfony\SymfonyResponseAdapter;
use Symfony\Component\HttpFoundation\Response;

describe('SymfonyResponseAdapter', function () {
    it('wraps a Response', function () {
        $resp = new Response('foo', 201, ['X-Test' => 'bar']);
        $adapter = new SymfonyResponseAdapter($resp);
        expect($adapter->getContent())->toBe('foo');
        expect($adapter->getStatusCode())->toBe(201);
        $adapter->setContent('bar');
        expect($adapter->getContent())->toBe('bar');
        $adapter->setStatusCode(404);
        expect($adapter->getStatusCode())->toBe(404);
    });
    it('make returns a new Response', function () {
        $adapter = new SymfonyResponseAdapter(new Response());
        $resp = $adapter->make('baz', 202, ['X-Foo' => 'baz']);
        expect($resp)->toBeInstanceOf(Response::class);
        expect($resp->getContent())->toBe('baz');
        expect($resp->getStatusCode())->toBe(202);
        expect($resp->headers->get('X-Foo'))->toBe('baz');
    });
});
