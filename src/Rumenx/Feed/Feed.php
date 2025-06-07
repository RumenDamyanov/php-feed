<?php

namespace Rumenx\Feed;

use Rumenx\Feed\FeedCacheInterface;
use Rumenx\Feed\FeedConfigInterface;
use Rumenx\Feed\FeedResponseInterface;
use Rumenx\Feed\FeedViewInterface;

/**
 * Feed generator class for laravel-feed package.
 *
 * This class is responsible for creating and rendering feeds in various formats
 * such as Atom and RSS. It supports caching, custom views, and various feed
 * configurations like title, description, link, and more.
 */

class Feed
{
    public const DEFAULT_REF = 'self';
    public const DEFAULT_FORMAT = 'atom';
    public const FORMAT_ATOM = 'application/atom+xml';
    public const FORMAT_RSS = 'application/rss+xml';
    public const DEFAULT_CACHE_KEY = 'laravel-feed';
    public const DEFAULT_SHORTENING_LIMIT = 150;
    public const DEFAULT_DATE_FORMAT = 'datetime';

    // Feed metadata
    private array $items = [];
    private string $title = 'My feed title';
    private string $subtitle = 'My feed subtitle';
    private string $description = 'My feed description';
    private ?string $domain = null;
    private ?string $link = null;
    private ?string $ref = null;
    private ?string $logo = null;
    private ?string $icon = null;
    private ?string $cover = null;
    private ?string $color = null;
    private ?string $ga = null;
    private bool $related = false;
    private ?string $copyright = null;
    private ?string $pubdate = null;
    private ?string $lang = null;
    private string $charset = 'utf-8';
    private ?string $ctype = null;
    private ?string $duration = null;
    private array $namespaces = [];

    // Feed config
    private int $caching = 0;
    private string $cacheKey = self::DEFAULT_CACHE_KEY;
    private bool $shortening = false;
    private int $shorteningLimit = self::DEFAULT_SHORTENING_LIMIT;
    private string $dateFormat = self::DEFAULT_DATE_FORMAT;
    private ?string $customView = null;

    // Dependencies
    private FeedCacheInterface $cache;
    private FeedConfigInterface $configRepository;
    private FeedResponseInterface $response;
    private FeedViewInterface $view;

    /**
     * Using constructor we populate our model from configuration file and loading dependencies
     * @param array $params
     */
    public function __construct(array $params)
    {
        foreach (["cache", "config", "response", "view"] as $dep) {
            if (!isset($params[$dep])) {
                throw new \InvalidArgumentException("Missing required dependency: $dep");
            }
        }
        $this->cache = $params['cache'];
        $this->configRepository = $params['config'];
        $this->response = $params['response'];
        $this->view = $params['view'];
    }

    /**
     * Add new items to $items array
     * @param array $item
     */
    public function addItem(array $item): void
    {
        // Robust multidimensional check
        if (array_is_list($item) && isset($item[0]) && is_array($item[0])) {
            foreach ($item as $i) {
                $this->addItem($i);
            }
            return;
        }
        if ($this->shortening && isset($item['description'])) {
            $append = (mb_strlen($item['description']) > $this->shorteningLimit) ? '...' : '';
            $item['description'] = mb_substr($item['description'], 0, $this->shorteningLimit, 'UTF-8') . $append;
        }
        if (isset($item['title'])) {
            $item['title'] = htmlspecialchars(strip_tags($item['title']), ENT_COMPAT, 'UTF-8');
        }
        if (isset($item['subtitle'])) {
            $item['subtitle'] = htmlspecialchars(strip_tags($item['subtitle']), ENT_COMPAT, 'UTF-8');
        }
        // Updated logic: set feed subtitle from item if feed subtitle is unset or empty string
        if ((empty($this->subtitle) || $this->subtitle === '') && !empty($item['subtitle'])) {
            $this->subtitle = $item['subtitle'];
        }
        $this->items[] = $item;
    }

    /**
     * Returns aggregated feed with all items from $items array
     * @param string|null $format (options: 'atom', 'rss')
     * @param int|null $cache (0 - turns off the cache)
     * @param string|null $key
     * @return mixed
     */
    public function render(?string $format = null, ?int $cache = null, ?string $key = null): mixed
    {
        $format = $format ?? self::DEFAULT_FORMAT;
        if ($cache === 0 || ($cache === null && $this->caching === 0)) {
            $this->clearCache();
        }
        if ($cache !== null && $cache > 0) {
            $this->caching = $cache;
        }
        if ($key !== null) {
            $this->cacheKey = $key;
        }
        $view = $this->customView ?? 'feed::' . $format;
        $ctype = $this->ctype ?? ($format === 'atom' ? self::FORMAT_ATOM : self::FORMAT_RSS);
        if ($this->caching > 0 && $this->cache->has($this->cacheKey)) {
            return $this->response->make(
                $this->cache->get($this->cacheKey),
                200,
                ['Content-Type' => $this->cache->get($this->cacheKey . '_ctype') . '; charset=' . $this->charset]
            );
        }
        $this->lang = $this->lang ?: $this->configRepository->get('application.language');
        $this->link = $this->link ?: $this->configRepository->get('application.url');
        $this->ref = $this->ref ?: self::DEFAULT_REF;
        $this->pubdate = $this->pubdate ?: date('D, d M Y H:i:s O');
        $rssLink = $this->domain ? sprintf('%s/%s', rtrim($this->domain, '/'), 'feed') : 'http://localhost/feed';
        $channel = [
            'title' => htmlspecialchars(strip_tags($this->title), ENT_COMPAT, 'UTF-8'),
            'subtitle' => htmlspecialchars(strip_tags($this->subtitle), ENT_COMPAT, 'UTF-8'),
            'description' => $this->description,
            'logo' => $this->logo,
            'icon' => $this->icon,
            'color' => $this->color,
            'cover' => $this->cover,
            'ga' => $this->ga,
            'related' => $this->related,
            'rssLink' => $rssLink,
            'link' => $this->link,
            'ref' => $this->ref,
            'pubdate' => $this->formatDate($this->pubdate, $format),
            'lang' => $this->lang,
            'copyright' => $this->copyright
        ];
        $viewData = [
            'items' => $this->items,
            'channel' => $channel,
            'namespaces' => $this->namespaces
        ];
        if ($this->caching > 0) {
            $this->cache->put($this->cacheKey, $this->view->make($view, $viewData)->render(), $this->caching);
            $this->cache->put($this->cacheKey . '_ctype', $ctype, $this->caching);
            return $this->response->make(
                $this->cache->get($this->cacheKey),
                200,
                ['Content-Type' => $this->cache->get($this->cacheKey . '_ctype') . '; charset=' . $this->charset]
            );
        }
        $this->clearCache();
        return $this->response->make(
            $this->view->make($view, $viewData),
            200,
            ['Content-Type' => $ctype . '; charset=' . $this->charset]
        );
    }

    public static function link(string $url, string $type = 'atom', ?string $title = null, ?string $lang = null): string
    {
        $type = in_array($type, ['rss', 'atom'], true) ? 'application/' . $type . '+xml' : $type;
        $titleAttr = $title ? ' title="' . $title . '"' : '';
        $langAttr = $lang ? ' hreflang="' . $lang . '"' : '';
        return '<link rel="alternate"' . $langAttr . ' type="' . $type . '" href="' . $url . '"' . $titleAttr . '>';
    }

    public function isCached(): bool
    {
        return $this->cache->has($this->cacheKey);
    }

    public function clearCache(): void
    {
        if ($this->isCached()) {
            $this->cache->forget($this->cacheKey);
        }
    }

    public function setCache(int $duration = 60, string $key = self::DEFAULT_CACHE_KEY): void
    {
        $this->cacheKey = $key;
        $this->caching = $duration;
        if ($duration < 1) {
            $this->clearCache();
        }
    }

    public function getCustomView(): ?string
    {
        return $this->customView;
    }
    public function setCustomView(?string $view = null): void
    {
        $this->customView = $view;
    }
    public function setTextLimit(int $l = self::DEFAULT_SHORTENING_LIMIT): void
    {
        $this->shorteningLimit = $l;
    }
    public function getTextLimit(): int
    {
        return $this->shorteningLimit;
    }
    public function setShortening(bool $b = false): void
    {
        $this->shortening = $b;
    }
    public function getShortening(): bool
    {
        return $this->shortening;
    }
    public function formatDate(mixed $date, string $format = 'atom'): string
    {
        if ($this->dateFormat === 'carbon' && is_object($date) && method_exists($date, 'toDateTimeString')) {
            $dateStr = $date->toDateTimeString();
            $date = ($format === 'atom') ? date('c', strtotime($dateStr)) : date('D, d M Y H:i:s O', strtotime($dateStr));
        } elseif ($this->dateFormat === 'timestamp') {
            $date = ($format === 'atom') ? date('c', strtotime('@' . $date)) : date('D, d M Y H:i:s O', strtotime('@' . $date));
        } else {
            $date = ($format === 'atom') ? date('c', strtotime((string)$date)) : date('D, d M Y H:i:s O', strtotime((string)$date));
        }
        return $date;
    }
    public function setDateFormat(string $format = self::DEFAULT_DATE_FORMAT): void
    {
        $this->dateFormat = $format;
    }
    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }
    public function getCacheDuration(): int
    {
        return $this->caching;
    }
    public function setNamespaces(array $namespaces): void
    {
        $this->namespaces = $namespaces;
    }
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }
    public function setShorteningLimit(int $limit): void
    {
        $this->shorteningLimit = $limit;
    }
    public function getShorteningLimit(): int
    {
        return $this->shorteningLimit;
    }
    // --- Metadata Getters/Setters ---
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function getSubtitle(): string { return $this->subtitle; }
    public function setSubtitle(string $subtitle): void { $this->subtitle = $subtitle; }
    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function getDomain(): ?string { return $this->domain; }
    public function setDomain(?string $domain): void { $this->domain = $domain; }
    public function getLink(): ?string { return $this->link; }
    public function setLink(?string $link): void { $this->link = $link; }
    public function getRef(): ?string { return $this->ref; }
    public function setRef(?string $ref): void { $this->ref = $ref; }
    public function getLogo(): ?string { return $this->logo; }
    public function setLogo(?string $logo): void { $this->logo = $logo; }
    public function getIcon(): ?string { return $this->icon; }
    public function setIcon(?string $icon): void { $this->icon = $icon; }
    public function getCover(): ?string { return $this->cover; }
    public function setCover(?string $cover): void { $this->cover = $cover; }
    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $color): void { $this->color = $color; }
    public function getGa(): ?string { return $this->ga; }
    public function setGa(?string $ga): void { $this->ga = $ga; }
    public function getRelated(): bool { return $this->related; }
    public function setRelated(bool $related): void { $this->related = $related; }
    public function getCopyright(): ?string { return $this->copyright; }
    public function setCopyright(?string $copyright): void { $this->copyright = $copyright; }
    public function getPubdate(): ?string { return $this->pubdate; }
    public function setPubdate(?string $pubdate): void { $this->pubdate = $pubdate; }
    public function getLang(): ?string { return $this->lang; }
    public function setLang(?string $lang): void { $this->lang = $lang; }
    public function getCharset(): string { return $this->charset; }
    public function setCharset(string $charset): void { $this->charset = $charset; }
    public function getCtype(): ?string { return $this->ctype; }
    public function setCtype(?string $ctype): void { $this->ctype = $ctype; }
    public function getDuration(): ?string { return $this->duration; }
    public function setDuration(?string $duration): void { $this->duration = $duration; }
}
