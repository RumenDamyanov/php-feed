<?php
use Rumenx\Feed\Feed;

test('feed link static method generates correct link', function () {
    $url = 'http://domain.tld/feed';
    expect(Feed::link($url, 'atom'))->toBe('<link rel="alternate" type="application/atom+xml" href="http://domain.tld/feed">');
    expect(Feed::link($url, 'rss'))->toBe('<link rel="alternate" type="application/rss+xml" href="http://domain.tld/feed">');
    expect(Feed::link($url, 'text/xml'))->toBe('<link rel="alternate" type="text/xml" href="http://domain.tld/feed">');
    expect(Feed::link($url, 'rss', 'Feed: RSS'))->toBe('<link rel="alternate" type="application/rss+xml" href="http://domain.tld/feed" title="Feed: RSS">');
    expect(Feed::link($url, 'atom', 'Feed: Atom', 'en'))->toBe('<link rel="alternate" hreflang="en" type="application/atom+xml" href="http://domain.tld/feed" title="Feed: Atom">');
});
