<?php
// FeedCacheInterfaceTest.php
// Unit tests for FeedCacheInterface implementations.

/**
 * @coversDefaultClass \Rumenx\Feed\FeedCacheInterface
 *
 * These tests verify that FeedCacheInterface implementations behave as expected.
 */

use Rumenx\Feed\Laravel\LaravelCacheAdapter;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;

describe('LaravelCacheAdapter', function () {
    it('covers has, get, put, and forget', function () {
        $repo = new Repository(new ArrayStore());
        $adapter = new LaravelCacheAdapter($repo);
        $key = 'foo';
        expect($adapter->has($key))->toBeFalse();
        $adapter->put($key, 'bar', 10);
        expect($adapter->has($key))->toBeTrue();
        expect($adapter->get($key))->toBe('bar');
        $adapter->forget($key);
        expect($adapter->has($key))->toBeFalse();
    });

    it('returns default if key missing', function () {
        $repo = new Repository(new ArrayStore());
        $adapter = new LaravelCacheAdapter($repo);
        expect($adapter->get('missing', 'default'))->toBe('default');
    });

    it('put works with 0 and negative TTL', function () {
        $repo = new Repository(new ArrayStore());
        $adapter = new LaravelCacheAdapter($repo);
        $adapter->put('zero', 'val', 0);
        expect($adapter->get('zero'))->toBeNull(); // ArrayStore does not persist with 0 TTL
        $adapter->put('neg', 'val2', -10);
        expect($adapter->get('neg'))->toBeNull(); // ArrayStore does not persist with negative TTL
    });

    it('forget on non-existent key does not error', function () {
        $repo = new Repository(new ArrayStore());
        $adapter = new LaravelCacheAdapter($repo);
        $adapter->forget('nope');
        expect(true)->toBeTrue(); // No exception
    });

    it('handles various value types', function () {
        $repo = new Repository(new ArrayStore());
        $adapter = new LaravelCacheAdapter($repo);
        $adapter->put('int', 123, 10);
        $adapter->put('arr', [1,2,3], 10);
        $adapter->put('obj', (object)['a'=>1], 10);
        expect($adapter->get('int'))->toBe(123);
        expect($adapter->get('arr'))->toBe([1,2,3]);
        expect($adapter->get('obj'))->toEqual((object)['a'=>1]);
    });
});
