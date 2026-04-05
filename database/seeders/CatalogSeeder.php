<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'home-living' => 'Home & Living',
            'lighting' => 'Lighting',
            'decor' => 'Decor',
            'stationery' => 'Stationery',
        ];

        foreach ($categories as $slug => $name) {
            Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $name . ' collection',
                    'is_active' => true,
                ],
            );
        }

        $products = [
            ['name' => 'Stripy Zig Zag Pillow', 'price' => 450000, 'image' => 'https://images.unsplash.com/photo-1555982105-d25af4182e4e?auto=format&fit=crop&w=400&h=400&q=80', 'category' => 'home-living'],
            ['name' => 'Woven Basket Tray', 'price' => 225000, 'image' => 'https://images.unsplash.com/photo-1508423134147-addf71308178?auto=format&fit=crop&w=400&h=400&q=80', 'category' => 'decor'],
            ['name' => 'Ceramic Candle Holder', 'price' => 195000, 'image' => 'https://images.unsplash.com/photo-1449247709967-d4461a6a6103?auto=format&fit=crop&w=400&h=400&q=80', 'category' => 'decor'],
            ['name' => 'Linen Table Runner', 'price' => 300000, 'image' => 'https://images.unsplash.com/reserve/LJIZlzHgQ7WPSh5KVTCB_Typewriter.jpg?auto=format&fit=crop&w=400&h=400&q=80', 'category' => 'home-living'],
            ['name' => 'Pine Wood Bookend', 'price' => 375000, 'image' => 'https://images.unsplash.com/photo-1467949576168-6ce8e2df4e13?auto=format&fit=crop&w=400&h=400&q=80', 'category' => 'home-living'],
            ['name' => 'Nordic Desk Lamp', 'price' => 599000, 'image' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?auto=format&fit=crop&w=400&h=400&q=80', 'category' => 'lighting'],
            ['name' => 'Soft Cotton Throw', 'price' => 525000, 'image' => 'https://images.unsplash.com/photo-1550837368-6594235de85c?auto=format&fit=crop&w=400&h=400&q=80', 'category' => 'home-living'],
            ['name' => 'Minimal Notebook', 'price' => 149000, 'image' => 'https://images.unsplash.com/photo-1551431009-a802eeec77b1?auto=format&fit=crop&w=400&h=400&q=80', 'category' => 'stationery'],
        ];

        $defaultCurrency = Currency::where('is_default', true)->first();

        if (! $defaultCurrency) {
            return;
        }

        // Rates: how many units of quote currency per 1 IDR
        $rates = [
            'GBP' => 0.000049,  // 1 IDR ≈ 0.000049 GBP  (1 GBP ≈ 20,350 IDR)
            'USD' => 0.000062,  // 1 IDR ≈ 0.000062 USD  (1 USD ≈ 16,100 IDR)
            'EUR' => 0.000057,  // 1 IDR ≈ 0.000057 EUR  (1 EUR ≈ 17,500 IDR)
        ];

        foreach ($rates as $quoteCode => $rate) {
            $quoteCurrency = Currency::where('code', $quoteCode)->first();

            if (! $quoteCurrency || $quoteCurrency->id === $defaultCurrency->id) {
                continue;
            }

            ExchangeRate::updateOrCreate(
                [
                    'base_currency_id' => $defaultCurrency->id,
                    'quote_currency_id' => $quoteCurrency->id,
                ],
                [
                    'rate' => $rate,
                    'provider' => 'seeded',
                    'fetched_at' => now(),
                ],
            );
        }

        foreach ($products as $index => $data) {
            $category = Category::where('slug', $data['category'])->first();

            if (! $category) {
                continue;
            }

            $slug = Str::slug($data['name']);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'sku' => 'TGRS-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'description' => 'Premium minimalist product by TGRS.CO',
                    'featured_image_url' => $data['image'],
                    'is_active' => true,
                ],
            );

            ProductPrice::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'currency_id' => $defaultCurrency->id,
                ],
                [
                    'amount_minor' => $data['price'],
                    'compare_at_minor' => null,
                ],
            );
        }

        $sizeAttribute = Attribute::updateOrCreate(
            ['slug' => 'size'],
            ['name' => 'Size', 'type' => 'text', 'is_active' => true],
        );

        $colorAttribute = Attribute::updateOrCreate(
            ['slug' => 'color'],
            ['name' => 'Color', 'type' => 'color', 'is_active' => true],
        );

        $sizes = [];
        foreach (['S', 'M', 'L'] as $i => $size) {
            $sizes[$size] = AttributeValue::updateOrCreate(
                ['attribute_id' => $sizeAttribute->id, 'value' => strtolower($size)],
                ['label' => $size, 'sort_order' => $i, 'is_active' => true],
            );
        }

        $colors = [
            'Charcoal' => '#222222',
            'Grey' => '#9CA3AF',
            'White' => '#FFFFFF',
        ];

        $colorValues = [];
        $idx = 0;
        foreach ($colors as $name => $hex) {
            $colorValues[$name] = AttributeValue::updateOrCreate(
                ['attribute_id' => $colorAttribute->id, 'value' => Str::slug($name)],
                ['label' => $name, 'hex_code' => $hex, 'sort_order' => $idx++, 'is_active' => true],
            );
        }

        $primaryProduct = Product::where('slug', Str::slug('Stripy Zig Zag Pillow'))->first();

        if ($primaryProduct) {
            $combinations = [
                ['color' => 'Charcoal', 'size' => 'S', 'price' => 450000],
                ['color' => 'Charcoal', 'size' => 'M', 'price' => 465000],
                ['color' => 'Charcoal', 'size' => 'L', 'price' => 480000],
                ['color' => 'Grey', 'size' => 'S', 'price' => 450000],
                ['color' => 'Grey', 'size' => 'M', 'price' => 465000],
                ['color' => 'White', 'size' => 'M', 'price' => 465000],
            ];

            foreach ($combinations as $combo) {
                $variant = ProductVariant::updateOrCreate(
                    [
                        'product_id' => $primaryProduct->id,
                        'sku' => sprintf('TGRS-V-%s-%s', strtoupper(substr($combo['color'], 0, 2)), $combo['size']),
                    ],
                    [
                        'name' => $combo['color'] . ' / ' . $combo['size'],
                        'featured_image_url' => $primaryProduct->featured_image_url,
                        'is_active' => true,
                    ],
                );

                $variant->attributeValues()->syncWithoutDetaching([
                    $colorValues[$combo['color']]->id,
                    $sizes[$combo['size']]->id,
                ]);

                ProductVariantPrice::updateOrCreate(
                    [
                        'product_variant_id' => $variant->id,
                        'currency_id' => $defaultCurrency->id,
                    ],
                    [
                        'amount_minor' => $combo['price'],
                        'compare_at_minor' => null,
                    ],
                );
            }
        }
    }
}
