<?php
// LaravelResponseAdapterTest.php
// Unit tests for the LaravelResponseAdapter.

/**
 * @covers \Rumenx\Feed\Laravel\LaravelResponseAdapter
 *
 * These tests verify that the LaravelResponseAdapter correctly delegates response creation to Laravel's ResponseFactory.
 */

use Rumenx\Feed\Laravel\LaravelResponseAdapter;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Http\Response;

describe('LaravelResponseAdapter', function () {
    it('makes a response', function () {
        $factory = new class implements \Illuminate\Contracts\Routing\ResponseFactory {
            public function make($content = '', $status = 200, array $headers = []) {
                return new \Illuminate\Http\Response($content, $status, $headers);
            }
            public function noContent($status = 204, array $headers = []) {}
            public function json($data = [], $status = 200, array $headers = [], $options = 0) {}
            public function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0) {}
            public function stream($callback, $status = 200, array $headers = []) {}
            public function streamDownload($callback, $name = null, array $headers = [], $disposition = 'attachment') {}
            public function download($file, $name = null, array $headers = [], $disposition = 'attachment') {}
            public function redirectTo($path, $status = 302, $headers = [], $secure = null) {}
            public function redirectToRoute($route, $parameters = [], $status = 302, $headers = []) {}
            public function redirectToAction($action, $parameters = [], $status = 302, $headers = []) {}
            public function redirectGuest($path, $status = 302, $headers = [], $secure = null) {}
            public function redirectToIntended($default = '/', $status = 302, $headers = [], $secure = null) {}
            public function view($view, $data = [], $status = 200, array $headers = []) {}
            public function file($file, array $headers = []) {}
        };
        $adapter = new LaravelResponseAdapter($factory);
        $resp = $adapter->make('foo', 201, ['X-Test' => 'bar']);
        expect($resp)->toBeInstanceOf(\Illuminate\Http\Response::class);
        expect($resp->getContent())->toBe('foo');
        expect($resp->getStatusCode())->toBe(201);
        expect($resp->headers->get('X-Test'))->toBe('bar');
    });
});
