<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<feed xmlns="http://www.w3.org/2005/Atom"<?php foreach ($namespaces as $n) { echo " " . $n; } ?>>
  <title><?php echo htmlspecialchars($channel['title']); ?></title>
  <subtitle><?php echo htmlspecialchars($channel['subtitle']); ?></subtitle>
  <link rel="alternate" type="text/html" href="<?php echo htmlspecialchars($channel['link']); ?>"/>
  <link rel="self" type="application/atom+xml" href="<?php echo htmlspecialchars($channel['rssLink']); ?>"/>
  <id><?php echo htmlspecialchars($channel['link']); ?></id>
  <updated><?php echo htmlspecialchars($channel['pubdate']); ?></updated>
  <?php if (!empty($channel['copyright'])): ?>
  <rights><?php echo htmlspecialchars($channel['copyright']); ?></rights>
  <?php endif; ?>

  <?php foreach ($items as $item): ?>
  <entry>
    <title type="html"><![CDATA[<?php echo $item['title']; ?>]]></title>
    <link rel="alternate" type="text/html" href="<?php echo htmlspecialchars($item['link']); ?>"/>
    <id><?php echo htmlspecialchars($item['link']); ?></id>
    <updated><?php echo htmlspecialchars($item['pubdate']); ?></updated>
    <summary type="html"><![CDATA[<?php echo $item['description']; ?>]]></summary>
    <content type="html"><![CDATA[<?php echo $item['description']; ?>]]></content>
    <author>
      <name><?php echo htmlspecialchars($item['author']); ?></name>
    </author>
    <?php if (!empty($item['category'])): ?>
    <?php foreach ((array)$item['category'] as $category): ?>
    <category term="<?php echo htmlspecialchars($category); ?>"/>
    <?php endforeach; ?>
    <?php endif; ?>
  </entry>
  <?php endforeach; ?>
</feed>
