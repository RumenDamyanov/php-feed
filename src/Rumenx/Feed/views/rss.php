<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:webfeeds="http://webfeeds.org/rss/1.0" xmlns:media="http://search.yahoo.com/mrss/"<?php foreach ($namespaces as $n) { echo " " . $n; } ?>>
  <channel>
    <title><?php echo htmlspecialchars($channel['title']); ?></title>
    <link><?php echo htmlspecialchars($channel['rssLink']); ?></link>
    <description><![CDATA[<?php echo $channel['description']; ?>]]></description>
    <atom:link href="<?php echo htmlspecialchars($channel['link']); ?>" rel="<?php echo htmlspecialchars($channel['ref']); ?>" type="application/rss+xml" />
    <?php if (!empty($channel['copyright'])): ?>
    <copyright><?php echo htmlspecialchars($channel['copyright']); ?></copyright>
    <?php endif; ?>
    <?php if (!empty($channel['color'])): ?>
    <webfeeds:accentColor><?php echo htmlspecialchars($channel['color']); ?></webfeeds:accentColor>
    <?php endif; ?>
    <?php if (!empty($channel['cover'])): ?>
    <webfeeds:cover image="<?php echo htmlspecialchars($channel['cover']); ?>" />
    <?php endif; ?>
    <?php if (!empty($channel['icon'])): ?>
    <webfeeds:icon><?php echo htmlspecialchars($channel['icon']); ?></webfeeds:icon>
    <?php endif; ?>
    <?php if (!empty($channel['logo'])): ?>
    <webfeeds:logo><?php echo htmlspecialchars($channel['logo']); ?></webfeeds:logo>
    <?php endif; ?>
    <?php if (!empty($channel['ga'])): ?>
    <webfeeds:analytics id="<?php echo htmlspecialchars($channel['ga']); ?>" engine="GoogleAnalytics"/>
    <?php endif; ?>
    <?php if (!empty($channel['related'])): ?>
    <webfeeds:related layout="card" target="browser"/>
    <?php endif; ?>
    <language><?php echo htmlspecialchars($channel['lang']); ?></language>
    <pubDate><?php echo htmlspecialchars($channel['pubdate']); ?></pubDate>

    <?php foreach ($items as $item): ?>
    <item>
      <title><![CDATA[<?php echo $item['title']; ?>]]></title>
      <link><?php echo htmlspecialchars($item['link']); ?></link>
      <description><![CDATA[<?php echo $item['description']; ?>]]></description>
      <author><?php echo htmlspecialchars($item['author']); ?></author>
      <guid><?php echo htmlspecialchars($item['link']); ?></guid>
      <pubDate><?php echo htmlspecialchars($item['pubdate']); ?></pubDate>
      <?php if (!empty($item['enclosure'])): ?>
      <enclosure url="<?php echo htmlspecialchars($item['enclosure']['url']); ?>" type="<?php echo htmlspecialchars($item['enclosure']['type']); ?>" <?php if (!empty($item['enclosure']['length'])): ?>length="<?php echo htmlspecialchars($item['enclosure']['length']); ?>"<?php endif; ?>/>
      <?php endif; ?>
      <?php if (!empty($item['category'])): ?>
      <?php foreach ((array)$item['category'] as $category): ?>
      <category><?php echo htmlspecialchars($category); ?></category>
      <?php endforeach; ?>
      <?php endif; ?>
    </item>
    <?php endforeach; ?>
  </channel>
</rss>
