<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'views_count',
        'description',
        'details',
        'shipping_information',
        'featured_image_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (blank($product->slug)) {
                $product->slug = static::generateUniqueSlug((string) $product->name);
            }

            if (blank($product->sku)) {
                $product->sku = static::generateUniqueSku((string) $product->name);
            }
        });

        static::updating(function (Product $product): void {
            if (blank($product->slug)) {
                $product->slug = static::generateUniqueSlug((string) $product->name, $product->id);
            }

            if (blank($product->sku)) {
                $product->sku = static::generateUniqueSku((string) $product->name, $product->id);
            }
        });
    }

    private static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if ($baseSlug === '') {
            $baseSlug = 'product';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (static::slugExists($slug, $ignoreId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private static function generateUniqueSku(string $name, ?int $ignoreId = null): string
    {
        $cleanName = preg_replace('/[^A-Za-z0-9]/', '', $name) ?? '';
        $prefix = strtoupper(Str::substr($cleanName, 0, 6));
        $prefix = $prefix !== '' ? $prefix : 'PRD';

        do {
            $sku = $prefix . '-' . strtoupper(Str::random(6));
        } while (static::skuExists($sku, $ignoreId));

        return $sku;
    }

    private static function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return static::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists();
    }

    private static function skuExists(string $sku, ?int $ignoreId = null): bool
    {
        return static::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('sku', $sku)
            ->exists();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variantPrices(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProductVariantPrice::class,
            ProductVariant::class,
            'product_id',
            'product_variant_id',
        );
    }
}
