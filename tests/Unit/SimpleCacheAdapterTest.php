<?php

/**
 * Unit tests for the SimpleCacheAdapter class.
 */
test('SimpleCacheAdapter can be instantiated', function () {
    $adapter = new \Rumenx\Feed\SimpleCacheAdapter();
    expect($adapter)->toBeInstanceOf(\Rumenx\Feed\SimpleCacheAdapter::class);
    expect($adapter)->toBeInstanceOf(\Rumenx\Feed\FeedCacheInterface::class);
});

test('SimpleCacheAdapter has method returns false initially', function () {
    $adapter = new \Rumenx\Feed\SimpleCacheAdapter();
    expect($adapter->has('test-key'))->toBeFalse();
});

test('SimpleCacheAdapter can store and retrieve values', function () {
    $adapter = new \Rumenx\Feed\SimpleCacheAdapter();

    $adapter->put('test-key', 'test-value', 3600);
    expect($adapter->has('test-key'))->toBeTrue();
    expect($adapter->get('test-key'))->toBe('test-value');
});

test('SimpleCacheAdapter get returns default for missing keys', function () {
    $adapter = new \Rumenx\Feed\SimpleCacheAdapter();
    expect($adapter->get('missing-key', 'default-value'))->toBe('default-value');
});

test('SimpleCacheAdapter can forget values', function () {
    $adapter = new \Rumenx\Feed\SimpleCacheAdapter();

    $adapter->put('test-key', 'test-value', 3600);
    expect($adapter->has('test-key'))->toBeTrue();

    $adapter->forget('test-key');
    expect($adapter->has('test-key'))->toBeFalse();
});

test('SimpleCacheAdapter forget on non-existent key does not error', function () {
    $adapter = new \Rumenx\Feed\SimpleCacheAdapter();
    $adapter->forget('non-existent-key');
    expect(true)->toBeTrue(); // If we get here, no error was thrown
});

test('SimpleCacheAdapter handles various value types', function () {
    $adapter = new \Rumenx\Feed\SimpleCacheAdapter();

    // Test string
    $adapter->put('string', 'hello', 3600);
    expect($adapter->get('string'))->toBe('hello');

    // Test array
    $adapter->put('array', ['a', 'b', 'c'], 3600);
    expect($adapter->get('array'))->toBe(['a', 'b', 'c']);

    // Test object
    $obj = (object)['prop' => 'value'];
    $adapter->put('object', $obj, 3600);
    expect($adapter->get('object'))->toEqual($obj);

    // Test null
    $adapter->put('null', null, 3600);
    expect($adapter->get('null'))->toBeNull();
});
