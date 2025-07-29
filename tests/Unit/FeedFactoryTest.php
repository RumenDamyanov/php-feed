<?php

/**
 * Unit tests for the FeedFactory class.
 */
test('FeedFactory can create a Feed instance', function () {
    $feed = \Rumenx\Feed\FeedFactory::create();
    expect($feed)->toBeInstanceOf(\Rumenx\Feed\Feed::class);
});

test('FeedFactory creates Feed with simple adapters', function () {
    $feed = \Rumenx\Feed\FeedFactory::create();

    // Set some basic properties to test the adapters work
    $feed->setTitle('Test Feed');
    $feed->setDescription('Test Description');
    $feed->setLink('https://example.com');

    expect($feed->getTitle())->toBe('Test Feed');
    expect($feed->getDescription())->toBe('Test Description');
    expect($feed->getLink())->toBe('https://example.com');
});

test('FeedFactory accepts custom config', function () {
    $config = [
        'use_cache' => false,
        'cache_key' => 'custom-key',
        'cache_duration' => 7200
    ];

    $feed = \Rumenx\Feed\FeedFactory::create($config);
    expect($feed)->toBeInstanceOf(\Rumenx\Feed\Feed::class);
});

test('FeedFactory created Feed can add items and render', function () {
    $feed = \Rumenx\Feed\FeedFactory::create();
    $feed->setTitle('Test Feed');
    $feed->setDescription('Test Description');
    $feed->setLink('https://example.com');

    $feed->addItem([
        'title' => 'Test Item',
        'description' => 'Test Description',
        'link' => 'https://example.com/test',
        'author' => 'Test Author',
        'pubdate' => date('r')
    ]);

    $rss = $feed->render('rss');
    expect($rss)->toBeObject();

    $rssContent = (string) $rss;
    expect($rssContent)->toBeString();
    expect($rssContent)->toContain('<?xml');
    expect($rssContent)->toContain('<rss');
    expect($rssContent)->toContain('Test Item');

    $atom = $feed->render('atom');
    expect($atom)->toBeObject();

    $atomContent = (string) $atom;
    expect($atomContent)->toBeString();
    expect($atomContent)->toContain('<?xml');
    expect($atomContent)->toContain('<feed');
    expect($atomContent)->toContain('Test Item');
});
