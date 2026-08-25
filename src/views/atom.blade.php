{!! '<'.'?'.'xml version="1.0" encoding="UTF-8" ?>' !!}
<feed xmlns="http://www.w3.org/2005/Atom"<?php foreach ($namespaces as $n) {
    echo " " . $n;
                                         } ?>>
  <title type="text">{!! $channel['title'] !!}</title>
  <subtitle type="html"><![CDATA[{!! \Rumenx\Feed\Feed::escapeCdata((string) ($channel['description'] ?? '')) !!}]]></subtitle>
  <link href="{{ $channel['rssLink'] }}"></link>
  <id>{{ $channel['link'] }}</id>
  <link rel="alternate" type="text/html" href="{{ $channel['rssLink'] }}" ></link>
  <link rel="{{ $channel['ref'] }}" type="application/atom+xml" href="{{ $channel['link'] }}" ></link>
@if (!empty($channel['logo']))
  <logo>{{ $channel['logo'] }}</logo>
@endif
@if (!empty($channel['icon']))
  <icon>{{ $channel['icon'] }}</icon>
@endif
    <updated>{{ $channel['pubdate'] }}</updated>
@foreach($items as $item)
    <entry>
      <author>
        <name>{{ $item['author'] }}</name>
      </author>
      <title type="text"><![CDATA[{!! \Rumenx\Feed\Feed::escapeCdata((string) ($item['title'] ?? '')) !!}]]></title>
      <link rel="alternate" type="text/html" href="{{ $item['link'] }}"></link>
      <id>{{ $item['link'] }}</id>
      <summary type="html"><![CDATA[{!! \Rumenx\Feed\Feed::escapeCdata((string) ($item['description'] ?? '')) !!}]]></summary>
      <content type="html"><![CDATA[{!! \Rumenx\Feed\Feed::escapeCdata((string) ($item['content'] ?? '')) !!}]]></content>
      <updated>{{ $item['pubdate'] }}</updated>
    </entry>
@endforeach
</feed>
