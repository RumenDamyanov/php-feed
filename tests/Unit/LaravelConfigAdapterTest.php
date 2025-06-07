<?php
// LaravelConfigAdapterTest.php
// Unit tests for the LaravelConfigAdapter.

/**
 * @covers \Rumenx\Feed\Laravel\LaravelConfigAdapter
 *
 * These tests verify that the LaravelConfigAdapter correctly delegates config access to Laravel's config repository.
 */

use Rumenx\Feed\Laravel\LaravelConfigAdapter;
use Illuminate\Config\Repository;

describe('LaravelConfigAdapter', function () {
    it('gets config value', function () {
        $repo = new Repository(['foo' => 'bar']);
        $adapter = new LaravelConfigAdapter($repo);
        expect($adapter->get('foo'))->toBe('bar');
    });
    it('returns default if missing', function () {
        $repo = new Repository([]);
        $adapter = new LaravelConfigAdapter($repo);
        expect($adapter->get('missing', 'default'))->toBe('default');
    });
});
