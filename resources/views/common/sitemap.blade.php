<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($form_data as $list_data)
    <url>
        <loc>{{$list_data['url']}}</loc>
        <lastmod>{{$list_data['lastmod']}}</lastmod>
        <changefreq>{{$list_data['changefreq']}}</changefreq>
        <priority>{{$list_data['priority']}}</priority>
    </url>
@endforeach
</urlset>