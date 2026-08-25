<?php

/**
 * Regression tests for GHSA-264h-qh97-mgwf:
 * XML injection via CDATA breakout in description/summary when `]]>` is not escaped.
 */

test('escapeCdata replaces CDATA terminators', function () {
    expect(\Rumenx\Feed\Feed::escapeCdata('DESC]]><evil/>'))->toBe('DESC]]&gt;<evil/>');
    expect(\Rumenx\Feed\Feed::escapeCdata('safe'))->toBe('safe');
    expect(\Rumenx\Feed\Feed::escapeCdata(''))->toBe('');
});

test('RSS render escapes CDATA breakout in item description via FeedFactory', function () {
    $feed = \Rumenx\Feed\FeedFactory::create();
    $feed->setTitle('News');
    $feed->setDescription('Channel ]]><evil ch="1"/>');
    $feed->setLink('https://example.com');
    $feed->addItem([
        'title' => 'Item',
        'description' => 'DESC]]><evil/>',
        'link' => 'https://example.com/item',
        'author' => 'Author',
        'pubdate' => date('r'),
        'content' => 'BODY]]><evil body="1"/>',
    ]);

    $xml = (string) $feed->render('rss');

    expect($xml)->not()->toContain(']]><evil');
    expect($xml)->toContain('<![CDATA[DESC]]&gt;<evil/>]]>');
    expect($xml)->toContain('<![CDATA[Channel ]]&gt;<evil ch="1"/>]]>');
});

test('Atom render escapes CDATA breakout in summary', function () {
    $feed = \Rumenx\Feed\FeedFactory::create();
    $feed->setTitle('News');
    $feed->setDescription('Channel');
    $feed->setLink('https://example.com');
    $feed->addItem([
        'title' => 'Item',
        'description' => 'x]]><script>alert(1)</script>',
        'link' => 'https://example.com/item',
        'author' => 'Author',
        'pubdate' => date('c'),
    ]);

    $xml = (string) $feed->render('atom');

    expect($xml)->not()->toContain(']]><script');
    expect($xml)->toContain('<![CDATA[x]]&gt;<script>alert(1)</script>]]>');
});

test('RSS view template escapes CDATA breakout when included directly', function () {
    $items = [[
        'title' => 'Title]]><evil t="1"/>',
        'description' => 'DESC]]><evil/>',
        'link' => 'https://example.com/item',
        'author' => 'Author',
        'pubdate' => date('r'),
    ]];
    $channel = [
        'title' => 'Feed',
        'description' => 'Channel]]><evil/>',
        'link' => 'https://example.com',
        'rssLink' => 'https://example.com/feed.rss',
        'ref' => 'self',
        'pubdate' => date('r'),
        'lang' => 'en',
    ];
    $namespaces = [];

    ob_start();
    include __DIR__ . '/../../src/Rumenx/Feed/views/rss.php';
    $xml = ob_get_clean();

    expect($xml)->not()->toContain(']]><evil');
    expect($xml)->toContain(']]&gt;<evil/>');
});
