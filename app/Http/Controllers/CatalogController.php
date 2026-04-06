<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Product;
use App\Services\CurrencyService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __construct(
        private readonly CurrencyService $currencyService,
        private readonly PricingService $pricingService,
    ) {
    }

    public function index(Request $request): View
    {
        $currency = $this->currencyService->getCurrentCurrency($request);

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->toArray();

        $products = Product::query()
            ->with(['category', 'prices', 'variants.prices'])
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function (Product $product) use ($currency) {
                $displayPriceMinor = $this->pricingService->resolveDisplayPriceMinor($product, $currency);

                return [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'views_count' => $product->views_count,
                    'has_price' => $displayPriceMinor > 0,
                    'price' => $this->pricingService->formatMinor($displayPriceMinor, $currency),
                    'image' => $this->resolveImageUrl($product->featured_image_url),
                    'category' => $product->category?->slug ?? 'uncategorized',
                ];
            })
            ->toArray();

        $banners = Banner::active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Banner $banner) => [
                'title'     => $banner->title,
                'subtitle'  => $banner->subtitle,
                'image'     => $this->resolveImageUrl($banner->image, false),
                'link_url'  => $banner->link_url,
                'link_text' => $banner->link_text ?: 'View Product',
            ])
            ->toArray();

        return view('home', [
            'products'         => $products,
            'categories'       => $categories,
            'banners'          => $banners,
            'currentCurrency'  => $currency,
            'activeCurrencies' => Currency::active()->orderBy('code')->get(),
        ]);
    }

    public function showProduct(Request $request, string $slug): View
    {
        $currency = $this->currencyService->getCurrentCurrency($request);

        $product = Product::query()
            ->with(['category', 'images', 'prices', 'variants.attributeValues.attribute', 'variants.prices'])
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $product->increment('views_count');
        $product->refresh();

        $activeVariants = $product->variants
            ->where('is_active', true)
            ->values();

        $variantOptions = $activeVariants->map(function ($variant) use ($currency) {
            $optionValues = [];
            $optionLabels = [];
            $optionMeta = [];
            $variantPriceMinor = $this->pricingService->resolveVariantPriceMinor($variant, $currency);

            foreach ($variant->attributeValues as $attributeValue) {
                if (! $attributeValue->attribute) {
                    continue;
                }

                $slug = $attributeValue->attribute->slug;
                $optionValues[$slug] = $attributeValue->value;
                $optionLabels[$slug] = $attributeValue->label;
                $optionMeta[$slug] = [
                    'hex' => $attributeValue->hex_code,
                ];
            }

            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'sku' => $variant->sku,
                'image' => $this->resolveImageUrl($variant->featured_image_url),
                'price_minor' => $variantPriceMinor,
                'has_price' => $variantPriceMinor > 0,
                'price' => $this->pricingService->formatMinor($variantPriceMinor, $currency),
                'option_values' => $optionValues,
                'option_labels' => $optionLabels,
                'option_meta' => $optionMeta,
            ];
        })->toArray();

        $optionGroups = [];

        foreach ($activeVariants as $variant) {
            foreach ($variant->attributeValues as $attributeValue) {
                if (! $attributeValue->attribute) {
                    continue;
                }

                $attributeSlug = $attributeValue->attribute->slug;

                if (! isset($optionGroups[$attributeSlug])) {
                    $optionGroups[$attributeSlug] = [
                        'label' => $attributeValue->attribute->name,
                        'type' => $attributeValue->attribute->type,
                        'options' => [],
                    ];
                }

                $optionGroups[$attributeSlug]['options'][$attributeValue->value] = [
                    'value' => $attributeValue->value,
                    'label' => $attributeValue->label,
                    'hex' => $attributeValue->hex_code,
                ];
            }
        }

        foreach ($optionGroups as $slug => $group) {
            $optionGroups[$slug]['options'] = array_values($group['options']);
        }

        $initialVariant = $variantOptions[0] ?? null;

        $initialVariantPriceMinor = data_get($initialVariant, 'price_minor');
        $initialVariantImage = data_get($initialVariant, 'image');
        $initialVariantSku = data_get($initialVariant, 'sku');

        $initialPriceMinor = $initialVariantPriceMinor
            ?? $this->pricingService->resolvePriceMinor($product, $currency);

        $initialPrice = $this->pricingService->formatMinor($initialPriceMinor, $currency);

        $initialImage = $initialVariantImage ?: $this->resolveImageUrl($product->featured_image_url);

        $productGalleryImages = $product->images
            ->pluck('url')
            ->map(fn (?string $path) => $this->resolveImageUrl($path, false))
            ->filter()
            ->toArray();

        $galleryImages = array_values(array_filter(array_unique(array_merge(
            [$initialImage],
            $productGalleryImages,
            array_column($variantOptions, 'image'),
        ))));

        $relatedProducts = Product::query()
            ->with(['prices', 'variants.prices'])
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get()
            ->map(function (Product $related) use ($currency) {
                $displayPriceMinor = $this->pricingService->resolveDisplayPriceMinor($related, $currency);

                return [
                    'id' => $related->id,
                    'slug' => $related->slug,
                    'name' => $related->name,
                    'views_count' => $related->views_count,
                    'image' => $this->resolveImageUrl($related->featured_image_url),
                    'has_price' => $displayPriceMinor > 0,
                    'price' => $this->pricingService->formatMinor($displayPriceMinor, $currency),
                ];
            })
            ->toArray();

        return view('product', [
            'id' => $product->id,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $initialVariantSku ?? $product->sku,
                'views_count' => $product->views_count,
                'has_price' => $initialPriceMinor > 0,
                'price' => $initialPrice,
                'image' => $initialImage,
                'category' => $product->category?->slug,
                'description' => $product->description,
                'details' => $product->details,
                'shipping_information' => $product->shipping_information,
            ],
            'categoryName' => $product->category?->name ?? 'General',
            'variantOptions' => $variantOptions,
            'optionGroups' => $optionGroups,
            'galleryImages' => $galleryImages,
            'relatedProducts' => $relatedProducts,
            'currentCurrency' => $currency,
            'activeCurrencies' => Currency::active()->orderBy('code')->get(),
        ]);
    }

    public function categories(Request $request): View
    {
        $currency = $this->currencyService->getCurrentCurrency($request);

        $categories = Category::query()
            ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function (Category $category) {
                $firstProduct = $category->products()->where('is_active', true)->first();

                return [
                    'slug' => $category->slug,
                    'name' => $category->name,
                    'count' => $category->products_count,
                    'image' => $this->resolveImageUrl($firstProduct?->featured_image_url),
                ];
            })
            ->toArray();

        return view('categories.index', [
            'categories' => $categories,
            'currentCurrency' => $currency,
            'activeCurrencies' => Currency::active()->orderBy('code')->get(),
        ]);
    }

    public function showCategory(Request $request, string $slug): View
    {
        $currency = $this->currencyService->getCurrentCurrency($request);

        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::query()
            ->with(['prices', 'variants.prices'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function (Product $product) use ($currency) {
                $displayPriceMinor = $this->pricingService->resolveDisplayPriceMinor($product, $currency);

                return [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'views_count' => $product->views_count,
                    'has_price' => $displayPriceMinor > 0,
                    'price' => $this->pricingService->formatMinor($displayPriceMinor, $currency),
                    'image' => $this->resolveImageUrl($product->featured_image_url),
                ];
            })
            ->toArray();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->toArray();

        return view('categories.show', [
            'categorySlug' => $category->slug,
            'categoryName' => $category->name,
            'products' => $products,
            'categories' => $categories,
            'currentCurrency' => $currency,
            'activeCurrencies' => Currency::active()->orderBy('code')->get(),
        ]);
    }

    public function setCurrency(Request $request, string $code): RedirectResponse
    {
        $this->currencyService->setCurrency($request, $code);

        return back();
    }

    public function showProductById(int $id): RedirectResponse
    {
        $product = Product::query()
            ->where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return redirect()->route('product.show', ['slug' => $product->slug], 301);
    }

    public function about(Request $request): View
    {
        $currency = $this->currencyService->getCurrentCurrency($request);

        $defaults = [
            'hero_title'    => 'About TGRS.CO',
            'hero_subtitle' => 'Your trusted partner for quality products sourced directly from China.',
            'hero_image'    => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=1600&q=80',
            'story_image'   => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80',
            'story_body'    => "TGRS.CO is your trusted bridge to the world's largest marketplace. We partner with verified suppliers and distributors across China to bring you a wide selection of quality products at competitive prices — from homeware and lifestyle goods to fashion, gadgets, and beyond.\n\nEvery product in our catalog is carefully reviewed before it reaches you. No more uncertainty when shopping internationally — we handle the sourcing, quality checks, and logistics so you can shop with confidence.\n\nCan't find what you're looking for? Our Jastip (Jasa Titip) service lets you request any item from China and our team will source and purchase it on your behalf.",
            'values_subtitle' => 'We built TGRS.CO around three principles that guide everything we do — from the suppliers we choose to the way we handle every order.',
            'value_1_title' => 'Verified Suppliers',
            'value_1_body'  => 'Every supplier in our network is personally vetted. We visit warehouses, check product quality, and verify business credentials before a single item appears in our catalog. You shop knowing the product is real, accurate, and ready to ship.',
            'value_2_title' => 'Competitive Prices',
            'value_2_body'  => 'By sourcing directly from Chinese manufacturers and distributors, we eliminate unnecessary layers of markup. What you see is a fair, transparent price — no hidden fees, no inflated retail margins.',
            'value_3_title' => 'Jastip Service',
            'value_3_body'  => 'Our catalog can\'t hold everything China has to offer. With our Jastip (Jasa Titip) service, you can request any item — send us a Taobao / 1688 link or a description, and we will buy it, check it, and ship it straight to you.',
            'jastip_title'        => 'How Jastip Works',
            'jastip_subtitle'     => "Can't find what you need in our catalog? We'll get it from China for you — just ask.",
            'jastip_step_1_title' => 'Tell Us What You Need',
            'jastip_step_1_body'  => 'Send us the product name, a link from Taobao / 1688 / Shopee, or simply describe what you are looking for via WhatsApp.',
            'jastip_step_2_title' => 'We Source & Purchase',
            'jastip_step_2_body'  => 'Our team locates the item from a trusted Chinese supplier, confirms the price and availability, and purchases it on your behalf.',
            'jastip_step_3_title' => 'We Deliver to You',
            'jastip_step_3_body'  => 'Your item is carefully inspected, packed, and shipped directly to your address. We keep you updated every step of the way.',
            'jastip_whatsapp'     => '',
            'team'                => json_encode([]),
        ];

        $settings = [];
        foreach ($defaults as $key => $default) {
            $settings[$key] = \App\Models\SiteSetting::get("about_{$key}", $default);
        }

        $settings['team_members'] = json_decode($settings['team'], true) ?: [];
        $settings['story_paragraphs'] = array_filter(
            array_map('trim', preg_split('/\n{2,}/', $settings['story_body']) ?: [])
        );

        return view('about', [
            'settings'         => $settings,
            'currentCurrency'  => $currency,
            'activeCurrencies' => Currency::active()->orderBy('code')->get(),
        ]);
    }

    public function sitemap()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->select(['id', 'slug', 'updated_at'])
            ->latest('updated_at')
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->select(['slug', 'updated_at'])
            ->orderBy('name')
            ->get();

        return response()
            ->view('sitemap', [
                'products' => $products,
                'categories' => $categories,
            ])
            ->header('Content-Type', 'application/xml');
    }

    private function resolveImageUrl(?string $path, bool $withFallback = true): ?string
    {
        if (! $path) {
            return $withFallback ? asset('images/placeholders/product.svg') : null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/storage/', 'data:'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
