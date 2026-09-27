<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
    <channel>
        <title>{{getTitle()}}（{{getTitleKana()}}）</title>
        <link>{{getSiteCanonical()}}</link>
        <description>{{getSiteDescription()}}</description>
        <lastBuildDate>{{$form_data['last_date']}}</lastBuildDate>
        <docs>https://validator.w3.org/feed/docs/rss2.html</docs>
        <ttl>0</ttl>
@foreach ($form_data['rss'] as $list_data)
        <item>
            <guid>{{$list_data['guid']}}</guid>
            <link>{{$list_data['link']}}</link>
            <title>{{$list_data['title']}}</title>
            <pubDate>{{$list_data['pubDate']}}</pubDate>
        </item>
@endforeach
	</channel>
</rss>