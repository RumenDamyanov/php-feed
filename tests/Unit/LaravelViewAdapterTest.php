<?php
// LaravelViewAdapterTest.php
// Unit tests for the LaravelViewAdapter.

use Rumenx\Feed\Laravel\LaravelViewAdapter;
use Illuminate\Contracts\View\Factory;

/**
 * @covers \Rumenx\Feed\Laravel\LaravelViewAdapter
 *
 * These tests verify that the LaravelViewAdapter correctly delegates view creation to Laravel's ViewFactory.
 */
describe('LaravelViewAdapter', function () {
    it('makes a view', function () {
        $factory = new class implements Factory {
            public function make($view, $data = [], $mergeData = []) {
                return $view . json_encode($data) . json_encode($mergeData);
            }
            public function exists($view) { return true; }
            public function file($path, $data = [], $mergeData = []) { return ''; }
            public function share($key, $value = null) { return null; }
            public function composer($views, $callback) { return []; }
            public function creator($views, $callback) { return []; }
            public function addNamespace($namespace, $hints) { return $this; }
            public function replaceNamespace($namespace, $hints) { return $this; }
        };
        $adapter = new LaravelViewAdapter($factory);
        $result = $adapter->make('foo', ['bar' => 'baz']);
        expect($result)->toBe('foo{"bar":"baz"}[]');
    });
});
