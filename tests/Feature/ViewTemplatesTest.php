<?php

/**
 * Feature tests for the view templates in the Feed package.
 */

defined('FEED_TEST_INCLUDE') || define('FEED_TEST_INCLUDE', true);

test('RSS view template can be included and generates valid XML', function () {
    // Define variables expected by the RSS template
    $items = [
        [
            'title' => 'Test Item',
            'description' => 'Test Description',
            'link' => 'https://example.com/test',
            'author' => 'Test Author',
            'pubdate' => date('r'),
            'guid' => 'https://example.com/test'
        ]
    ];

    $channel = [
        'title' => 'Test Feed',
        'description' => 'Test Description',
        'link' => 'https://example.com',
        'rssLink' => 'https://example.com/feed.rss',
        'ref' => 'self',
        'pubdate' => date('r'),
        'lang' => 'en',
        'copyright' => 'Test Copyright',
        'subtitle' => 'Test Subtitle',
        'color' => '#ff0000',
        'cover' => 'https://example.com/cover.jpg',
        'icon' => 'https://example.com/icon.ico',
        'logo' => 'https://example.com/logo.png',
        'ga' => 'UA-12345',
        'related' => 'https://example.com/related'
    ];

    $namespaces = [];

    // Capture output from the RSS template
    ob_start();
    include __DIR__ . '/../../src/Rumenx/Feed/views/rss.php';
    $rssContent = ob_get_clean();

    expect($rssContent)->toContain('<?xml version="1.0" encoding="UTF-8" ?>');
    expect($rssContent)->toContain('<rss version="2.0"');
    expect($rssContent)->toContain('<channel>');
    expect($rssContent)->toContain('<title>Test Feed</title>');
    expect($rssContent)->toContain('<description><![CDATA[Test Description]]></description>');
    expect($rssContent)->toContain('<link>https://example.com/feed.rss</link>');
    expect($rssContent)->toContain('<item>');
    expect($rssContent)->toContain('Test Item');
    expect($rssContent)->toContain('Test Author');
    expect($rssContent)->toContain('</channel>');
    expect($rssContent)->toContain('</rss>');
});

test('Atom view template can be included and generates valid XML', function () {
    // Define variables expected by the Atom template
    $items = [
        [
            'title' => 'Test Item',
            'description' => 'Test Description',
            'link' => 'https://example.com/test',
            'author' => 'Test Author',
            'pubdate' => date('c'),
            'guid' => 'https://example.com/test'
        ]
    ];

    $channel = [
        'title' => 'Test Feed',
        'description' => 'Test Description',
        'link' => 'https://example.com',
        'rssLink' => 'https://example.com/feed.atom',
        'ref' => 'self',
        'pubdate' => date('c'),
        'lang' => 'en',
        'copyright' => 'Test Copyright',
        'subtitle' => 'Test Subtitle',
        'color' => '#ff0000',
        'cover' => 'https://example.com/cover.jpg',
        'icon' => 'https://example.com/icon.ico',
        'logo' => 'https://example.com/logo.png',
        'ga' => 'UA-12345',
        'related' => 'https://example.com/related'
    ];

    $namespaces = [];

    // Capture output from the Atom template
    ob_start();
    include __DIR__ . '/../../src/Rumenx/Feed/views/atom.php';
    $atomContent = ob_get_clean();

    expect($atomContent)->toContain('<?xml version="1.0" encoding="UTF-8" ?>');
    expect($atomContent)->toContain('<feed xmlns="http://www.w3.org/2005/Atom">');
    expect($atomContent)->toContain('<title>Test Feed</title>');
    expect($atomContent)->toContain('<subtitle>Test Subtitle</subtitle>');
    expect($atomContent)->toContain('<link rel="alternate" type="text/html" href="https://example.com"/>');
    expect($atomContent)->toContain('<entry>');
    expect($atomContent)->toContain('Test Item');
    expect($atomContent)->toContain('<name>Test Author</name>');
    expect($atomContent)->toContain('</entry>');
    expect($atomContent)->toContain('</feed>');
});

test('RSS template handles empty items array', function () {
    $items = [];
    $channel = [
        'title' => 'Empty Feed',
        'description' => 'No items',
        'link' => 'https://example.com',
        'rssLink' => 'https://example.com/feed.rss',
        'ref' => 'self',
        'pubdate' => date('r')
    ];
    $namespaces = [];

    ob_start();
    include __DIR__ . '/../../src/Rumenx/Feed/views/rss.php';
    $rssContent = ob_get_clean();

    expect($rssContent)->toContain('<channel>');
    expect($rssContent)->toContain('<title>Empty Feed</title>');
    expect($rssContent)->toContain('</channel>');
    expect($rssContent)->not->toContain('<item>');
});

test('Atom template handles empty items array', function () {
    $items = [];
    $channel = [
        'title' => 'Empty Feed',
        'description' => 'No items',
        'link' => 'https://example.com',
        'rssLink' => 'https://example.com/feed.atom',
        'ref' => 'self',
        'pubdate' => date('c')
    ];
    $namespaces = [];

    ob_start();
    include __DIR__ . '/../../src/Rumenx/Feed/views/atom.php';
    $atomContent = ob_get_clean();

    expect($atomContent)->toContain('<feed xmlns="http://www.w3.org/2005/Atom">');
    expect($atomContent)->toContain('<title>Empty Feed</title>');
    expect($atomContent)->toContain('</feed>');
    expect($atomContent)->not->toContain('<entry>');
});

test('templates handle special characters correctly', function () {
    $items = [
        [
            'title' => 'Title with & special < chars >',
            'description' => 'Description with "quotes" and \'apostrophes\'',
            'link' => 'https://example.com/test?param=value&other=data',
            'author' => 'Author & Co.',
            'pubdate' => date('r')
        ]
    ];

    $channel = [
        'title' => 'Feed & Title',
        'description' => 'Description with <special> chars',
        'link' => 'https://example.com?test=true&other=false',
        'rssLink' => 'https://example.com/feed.rss',
        'ref' => 'self',
        'pubdate' => date('r')
    ];
    $namespaces = [];

    // Test RSS template
    ob_start();
    include __DIR__ . '/../../src/Rumenx/Feed/views/rss.php';
    $rssContent = ob_get_clean();

    expect($rssContent)->toContain('<![CDATA[');

    // Test Atom template
    ob_start();
    include __DIR__ . '/../../src/Rumenx/Feed/views/atom.php';
    $atomContent = ob_get_clean();

    expect($atomContent)->toContain('<![CDATA[');
});

test('templates handle categories and enclosures', function () {
    $items = [
        [
            'title' => 'Test Item with Media',
            'description' => 'Test Description',
            'link' => 'https://example.com/test',
            'author' => 'Test Author',
            'pubdate' => date('r'),
            'category' => ['Technology', 'Programming'],
            'enclosure' => [
                'url' => 'https://example.com/file.mp3',
                'type' => 'audio/mpeg',
                'length' => '1024'
            ]
        ]
    ];

    $channel = [
        'title' => 'Test Feed',
        'description' => 'Test Description',
        'link' => 'https://example.com',
        'rssLink' => 'https://example.com/feed.rss',
        'ref' => 'self',
        'pubdate' => date('r')
    ];
    $namespaces = [];

    // Test RSS template with categories and enclosures
    ob_start();
    include __DIR__ . '/../../src/Rumenx/Feed/views/rss.php';
    $rssContent = ob_get_clean();

    expect($rssContent)->toContain('<category>Technology</category>');
    expect($rssContent)->toContain('<category>Programming</category>');
    expect($rssContent)->toContain('<enclosure url="https://example.com/file.mp3"');
    expect($rssContent)->toContain('type="audio/mpeg"');
    expect($rssContent)->toContain('length="1024"');

    // Test Atom template with categories
    $channel['rssLink'] = 'https://example.com/feed.atom';
    ob_start();
    include __DIR__ . '/../../src/Rumenx/Feed/views/atom.php';
    $atomContent = ob_get_clean();

    expect($atomContent)->toContain('<category term="Technology"/>');
    expect($atomContent)->toContain('<category term="Programming"/>');
});
