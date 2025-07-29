<?php

namespace Rumenx\Feed;

/**
 * Simple view adapter that generates basic RSS/Atom feeds without external templates.
 * Used for plain PHP applications where no template engine is available.
 */
class SimpleViewAdapter implements FeedViewInterface
{
    /**
     * Create a view instance and render it.
     *
     * @param string $view
     * @param array<string, mixed> $data
     * @return mixed
     */
    public function make(string $view, array $data = []): mixed
    {
        $items = $data['items'] ?? [];
        $channel = $data['channel'] ?? [];

        // Set defaults
        $channel['title'] = $channel['title'] ?? 'Feed';
        $channel['description'] = $channel['description'] ?? 'Feed Description';
        $channel['link'] = $channel['link'] ?? 'http://localhost';
        $channel['rssLink'] = $channel['rssLink'] ?? 'http://localhost/feed';
        $channel['pubdate'] = $channel['pubdate'] ?? date('r');

        if (str_contains($view, 'atom')) {
            $content = $this->generateAtom($items, $channel);
        } else {
            $content = $this->generateRss($items, $channel);
        }

        // Return object that has a render method for compatibility
        return new class($content) {
            public function __construct(private string $content) {}

            public function render(): string {
                return $this->content;
            }

            public function __toString(): string {
                return $this->content;
            }
        };
    }

    /**
     * @param array<int, array<string, mixed>> $items
     * @param array<string, mixed> $channel
     */
    private function generateRss(array $items, array $channel): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
        $xml .= '  <channel>' . "\n";
        $xml .= '    <title>' . htmlspecialchars($channel['title']) . '</title>' . "\n";
        $xml .= '    <link>' . htmlspecialchars($channel['link']) . '</link>' . "\n";
        $xml .= '    <description><![CDATA[' . $channel['description'] . ']]></description>' . "\n";
        $xml .= '    <pubDate>' . $channel['pubdate'] . '</pubDate>' . "\n";

        foreach ($items as $item) {
            $xml .= '    <item>' . "\n";
            $xml .= '      <title><![CDATA[' . ($item['title'] ?? '') . ']]></title>' . "\n";
            $xml .= '      <link>' . htmlspecialchars($item['link'] ?? '') . '</link>' . "\n";
            $xml .= '      <description><![CDATA[' . ($item['description'] ?? '') . ']]></description>' . "\n";
            $xml .= '      <author>' . htmlspecialchars($item['author'] ?? '') . '</author>' . "\n";
            $xml .= '      <pubDate>' . ($item['pubdate'] ?? date('r')) . '</pubDate>' . "\n";
            $xml .= '      <guid>' . htmlspecialchars($item['link'] ?? '') . '</guid>' . "\n";
            $xml .= '    </item>' . "\n";
        }

        $xml .= '  </channel>' . "\n";
        $xml .= '</rss>';

        return $xml;
    }

    /**
     * @param array<int, array<string, mixed>> $items
     * @param array<string, mixed> $channel
     */
    private function generateAtom(array $items, array $channel): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<feed xmlns="http://www.w3.org/2005/Atom">' . "\n";
        $xml .= '  <title>' . htmlspecialchars($channel['title']) . '</title>' . "\n";
        $xml .= '  <link rel="alternate" type="text/html" href="' . htmlspecialchars($channel['link']) . '"/>' . "\n";
        $xml .= '  <link rel="self" type="application/atom+xml" href="' . htmlspecialchars($channel['rssLink']) . '"/>' . "\n";
        $xml .= '  <id>' . htmlspecialchars($channel['link']) . '</id>' . "\n";
        $xml .= '  <updated>' . date('c') . '</updated>' . "\n";
        if (!empty($channel['subtitle'])) {
            $xml .= '  <subtitle>' . htmlspecialchars($channel['subtitle']) . '</subtitle>' . "\n";
        }

        foreach ($items as $item) {
            $xml .= '  <entry>' . "\n";
            $xml .= '    <title type="html"><![CDATA[' . ($item['title'] ?? '') . ']]></title>' . "\n";
            $xml .= '    <link rel="alternate" type="text/html" href="' . htmlspecialchars($item['link'] ?? '') . '"/>' . "\n";
            $xml .= '    <id>' . htmlspecialchars($item['link'] ?? '') . '</id>' . "\n";
            $xml .= '    <updated>' . date('c', strtotime($item['pubdate'] ?? 'now')) . '</updated>' . "\n";
            $xml .= '    <summary type="html"><![CDATA[' . ($item['description'] ?? '') . ']]></summary>' . "\n";
            $xml .= '    <author>' . "\n";
            $xml .= '      <name>' . htmlspecialchars($item['author'] ?? '') . '</name>' . "\n";
            $xml .= '    </author>' . "\n";
            $xml .= '  </entry>' . "\n";
        }

        $xml .= '</feed>';

        return $xml;
    }
}
