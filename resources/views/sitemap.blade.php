<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticRoutes as $name)
    <url>
        <loc>{{ route($name) }}</loc>
    </url>
@endforeach
@foreach ($posts as $post)
    <url>
        <loc>{{ route('posts.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
    </url>
@endforeach
</urlset>
