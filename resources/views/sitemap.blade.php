{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/categories') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>

    @foreach($categories as $category)
        <url>
            <loc>{{ url('/categories/' . $category->slug) }}</loc>
            @if($category->updated_at)
                <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    @foreach($products as $product)
        <url>
            <loc>{{ url('/product/' . ($product->slug ?: $product->id)) }}</loc>
            @if($product->updated_at)
                <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>
