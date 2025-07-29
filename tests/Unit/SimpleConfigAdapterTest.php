<?php

/**
 * Unit tests for the SimpleConfigAdapter class.
 */
test('SimpleConfigAdapter can be instantiated', function () {
    $adapter = new \Rumenx\Feed\SimpleConfigAdapter();
    expect($adapter)->toBeInstanceOf(\Rumenx\Feed\SimpleConfigAdapter::class);
    expect($adapter)->toBeInstanceOf(\Rumenx\Feed\FeedConfigInterface::class);
});

test('SimpleConfigAdapter can be instantiated with config', function () {
    $config = ['custom_key' => 'custom_value'];
    $adapter = new \Rumenx\Feed\SimpleConfigAdapter($config);
    expect($adapter)->toBeInstanceOf(\Rumenx\Feed\SimpleConfigAdapter::class);
});

test('SimpleConfigAdapter returns default values', function () {
    $adapter = new \Rumenx\Feed\SimpleConfigAdapter();

    expect($adapter->get('application.language'))->toBe('en');
    expect($adapter->get('application.url'))->toBe('http://localhost');
    expect($adapter->get('non_existent_key'))->toBeNull();
    expect($adapter->get('non_existent_key', 'default_value'))->toBe('default_value');
});

test('SimpleConfigAdapter returns custom config values', function () {
    $config = [
        'use_cache' => true,
        'cache_key' => 'custom-key',
        'cache_duration' => 7200,
        'custom_setting' => 'custom_value'
    ];
    $adapter = new \Rumenx\Feed\SimpleConfigAdapter($config);

    expect($adapter->get('use_cache'))->toBeTrue();
    expect($adapter->get('cache_key'))->toBe('custom-key');
    expect($adapter->get('cache_duration'))->toBe(7200);
    expect($adapter->get('custom_setting'))->toBe('custom_value');
});

test('SimpleConfigAdapter returns default for missing keys', function () {
    $adapter = new \Rumenx\Feed\SimpleConfigAdapter();
    expect($adapter->get('non_existent_key', 'default_value'))->toBe('default_value');
});

test('SimpleConfigAdapter handles nested config access', function () {
    $config = [
        'nested' => [
            'deep' => [
                'value' => 'found'
            ]
        ]
    ];
    $adapter = new \Rumenx\Feed\SimpleConfigAdapter($config);

    // Note: This adapter uses simple array access, not dot notation
    expect($adapter->get('nested'))->toBe(['deep' => ['value' => 'found']]);
});
