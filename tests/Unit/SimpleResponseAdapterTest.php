<?php

/**
 * Unit tests for the SimpleResponseAdapter class.
 */
test('SimpleResponseAdapter can be instantiated', function () {
    $adapter = new \Rumenx\Feed\SimpleResponseAdapter();
    expect($adapter)->toBeInstanceOf(\Rumenx\Feed\SimpleResponseAdapter::class);
    expect($adapter)->toBeInstanceOf(\Rumenx\Feed\FeedResponseInterface::class);
});

test('SimpleResponseAdapter make returns the content as string', function () {
    $adapter = new \Rumenx\Feed\SimpleResponseAdapter();
    $content = '<xml>test content</xml>';

    $result = $adapter->make($content);
    expect($result)->toBe($content);
});

test('SimpleResponseAdapter make handles different content types', function () {
    $adapter = new \Rumenx\Feed\SimpleResponseAdapter();

    // Test string content
    $stringContent = 'Hello World';
    expect($adapter->make($stringContent))->toBe($stringContent);

    // Test XML content
    $xmlContent = '<?xml version="1.0"?><root><item>test</item></root>';
    expect($adapter->make($xmlContent))->toBe($xmlContent);

    // Test empty content
    $emptyContent = '';
    expect($adapter->make($emptyContent))->toBe($emptyContent);
});

test('SimpleResponseAdapter preserves special characters', function () {
    $adapter = new \Rumenx\Feed\SimpleResponseAdapter();
    $content = 'Content with special chars: & < > " \'';

    $result = $adapter->make($content);
    expect($result)->toBe($content);
});

test('SimpleResponseAdapter handles large content', function () {
    $adapter = new \Rumenx\Feed\SimpleResponseAdapter();
    $content = str_repeat('Large content block. ', 1000);

    $result = $adapter->make($content);
    expect($result)->toBe($content);
    expect(strlen($result))->toBe(strlen($content));
});
