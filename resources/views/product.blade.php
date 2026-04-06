@extends('layouts.app')

@section('title', $product['name'] ?? 'Product Details')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product['description'] ?? 'Buy this product from trusted China suppliers via TGRS.CO.'), 155))
@section('meta_image', $product['image'] ?? asset('favicon.svg'))

@section('content')

    {{-- Breadcrumb --}}
    <section class="bg-white py-4 border-b border-gray-200">
        <div class="container mx-auto px-6">
            <nav class="text-sm text-gray-500">
                <a href="{{ url('/') }}" class="hover:text-black hover:underline">Shop</a>
                <span class="mx-2">/</span>
                <a href="{{ url('/categories/' . ($product['category'] ?? 'home-living')) }}" class="hover:text-black hover:underline">{{ $categoryName ?? 'Category' }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800">Product Details</span>
            </nav>
        </div>
    </section>

    {{-- Product Detail --}}
    <section class="bg-white py-10">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap -mx-4">

                {{-- Product Images --}}
                <div class="w-full md:w-1/2 px-4 mb-8 md:mb-0">
                    <div class="mb-4">
                        <img id="main-image"
                            src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1555982105-d25af4182e4e?auto=format&fit=crop&w=800&q=80' }}"
                            alt="{{ $product['name'] ?? 'Product Image' }}"
                            class="w-full object-contain hover:grow hover:shadow-lg cursor-zoom-in"
                            onclick="openImagePreview(this.src)"
                            style="height: 480px;">
                    </div>
                    {{-- Thumbnails --}}
                    <div class="flex space-x-3">
                        @foreach(($galleryImages ?? []) as $image)
                            <img onclick="setMainImage(this.src)"
                                src="{{ $image }}"
                                class="w-20 h-20 object-cover cursor-pointer border border-gray-300 hover:border-gray-800"
                                alt="{{ $product['name'] ?? 'Product thumbnail' }}">
                        @endforeach
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="w-full md:w-1/2 px-4">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $product['name'] ?? 'Stripy Zig Zag Jigsaw Pillow' }}</h1>

                    <p id="product-price" class="text-xl text-gray-800 mb-4">{{ $product['price'] ?? '£29.99' }}</p>

                    <p class="text-gray-600 mb-6 leading-relaxed">
                        {{ $product['description'] ?: 'No description available yet.' }}
                    </p>

                    {{-- Divider --}}
                    <hr class="border-gray-200 mb-6">

                    {{-- Color --}}
                    @if(!empty($optionGroups['color']['options']))
                        <div class="mb-5">
                            <p class="text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wide">{{ $optionGroups['color']['label'] ?? 'Colour' }}</p>
                            <div class="flex space-x-2 flex-wrap">
                                @foreach($optionGroups['color']['options'] as $option)
                                    <button type="button"
                                        onclick="selectOption(this, 'color', '{{ $option['value'] }}')"
                                        data-attribute="color"
                                        data-value="{{ $option['value'] }}"
                                        title="{{ $option['label'] }}"
                                        class="variant-option-btn rounded-full border-2 border-gray-300 hover:border-gray-800 focus:outline-none"
                                        style="width: 1.75rem; height: 1.75rem; background-color: {{ $option['hex'] ?: '#D1D5DB' }};">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Size --}}
                    @if(!empty($optionGroups['size']['options']))
                        <div class="mb-6">
                            <p class="text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wide">{{ $optionGroups['size']['label'] ?? 'Size' }}</p>
                            <div class="flex space-x-2 flex-wrap">
                                @foreach($optionGroups['size']['options'] as $option)
                                    <button type="button"
                                        onclick="selectOption(this, 'size', '{{ $option['value'] }}')"
                                        data-attribute="size"
                                        data-value="{{ $option['value'] }}"
                                        class="variant-option-btn px-4 py-2 text-sm border border-gray-300 text-gray-600 hover:border-gray-800 hover:text-gray-800 focus:outline-none">
                                        {{ $option['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Quantity --}}
                    <div class="mb-6">
                        <p class="text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wide">Quantity</p>
                        <div class="flex items-center border border-gray-300 w-32">
                            <button onclick="changeQty(-1)" class="px-3 py-2 text-gray-600 hover:text-black focus:outline-none">−</button>
                            <input id="qty" type="number" value="1" min="1"
                                class="w-full text-center text-gray-800 border-l border-r border-gray-300 py-2 focus:outline-none">
                            <button onclick="changeQty(1)" class="px-3 py-2 text-gray-600 hover:text-black focus:outline-none">+</button>
                        </div>
                    </div>

                    {{-- Order Now --}}
                    <div class="mb-6">
                        <a href="#" onclick="return orderOnWhatsApp()"
                            class="flex items-center justify-center w-full bg-green-500 text-white py-3 px-6 uppercase tracking-widest text-sm hover:bg-green-600 focus:outline-none">
                            <svg class="w-5 h-5 mr-2 fill-current shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Order Now via WhatsApp
                        </a>
                    </div>

                    {{-- Meta --}}
                    <hr class="border-gray-200 mb-4">
                    <p class="text-sm text-gray-500">SKU: <span id="product-sku" class="text-gray-700">{{ $product['sku'] ?? 'N/A' }}</span></p>
                    <p class="text-sm text-gray-500 mt-1">Category: <a href="{{ url('/categories/' . ($product['category'] ?? 'home-living')) }}" class="text-gray-700 hover:underline">{{ $categoryName ?? 'Home & Living' }}</a></p>
                </div>
            </div>
        </div>
    </section>

    {{-- Product Description Tabs --}}
    <section class="bg-white border-t border-gray-200 py-10">
        <div class="container mx-auto px-6">
            <div class="flex border-b border-gray-200 mb-6">
                <button onclick="showTab('description')"
                    id="tab-description"
                    class="tab-btn py-3 px-6 text-sm uppercase tracking-wide border-b-2 border-gray-800 text-gray-800 font-semibold focus:outline-none mr-2">
                    Description
                </button>
                <button onclick="showTab('details')"
                    id="tab-details"
                    class="tab-btn py-3 px-6 text-sm uppercase tracking-wide border-b-2 border-transparent text-gray-500 hover:text-gray-800 focus:outline-none mr-2">
                    Details
                </button>
                <button onclick="showTab('shipping')"
                    id="tab-shipping"
                    class="tab-btn py-3 px-6 text-sm uppercase tracking-wide border-b-2 border-transparent text-gray-500 hover:text-gray-800 focus:outline-none">
                    Shipping
                </button>
            </div>

            <div id="content-description" class="tab-content text-gray-600 leading-relaxed max-w-2xl">
                {!! nl2br(e($product['description'] ?: 'No description available yet.')) !!}
            </div>
            <div id="content-details" class="tab-content hidden text-gray-600 leading-relaxed max-w-2xl">
                {!! nl2br(e($product['details'] ?: 'No product details available yet.')) !!}
            </div>
            <div id="content-shipping" class="tab-content hidden text-gray-600 leading-relaxed max-w-2xl">
                {!! nl2br(e($product['shipping_information'] ?: 'No shipping information available yet.')) !!}
            </div>
        </div>
    </section>

    {{-- Related Products --}}
    <section class="bg-white py-10 border-t border-gray-200">
        <div class="container mx-auto px-6">
            <h2 class="uppercase tracking-wide font-bold text-gray-800 text-xl mb-8">You May Also Like</h2>
            <div class="flex flex-wrap -mx-3">
                @forelse(($relatedProducts ?? []) as $item)
                    <div class="w-full md:w-1/2 xl:w-1/4 px-3 mb-6">
                        <a href="{{ url('/product/' . $item['slug']) }}">
                            <img class="hover:grow hover:shadow-lg w-full" src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                            <div class="pt-3">
                                <p class="text-gray-700">{{ $item['name'] }}</p>
                            </div>
                            <p class="pt-1 text-gray-900">{{ $item['price'] }}</p>
                        </a>
                    </div>
                @empty
                    <div class="w-full px-3">
                        <p class="text-gray-500">No related products yet.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    {{-- Image Preview Modal --}}
    <div id="image-preview-modal" class="fixed inset-0 z-50 hidden bg-black backdrop-blur-[1px] items-center justify-center p-4 sm:p-6 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.82);" onclick="closeImagePreview(event)">
        <div class="relative w-auto max-w-3xl my-6" onclick="event.stopPropagation()">
            <button type="button"
                class="absolute top-2 right-2 z-10 w-9 h-9 rounded-full bg-black/80 text-white text-xl leading-none hover:bg-black focus:outline-none"
                onclick="closeImagePreview()"
                aria-label="Close image preview">×</button>

            <div class="bg-white rounded-lg p-3 shadow-2xl">
                <div class="overflow-hidden rounded">
                    <img id="image-preview-target"
                        src=""
                        alt="Image Preview"
                        class="block mx-auto max-w-full max-h-[70svh] object-contain rounded transition-transform duration-300 ease-out hover:scale-110 cursor-zoom-in"
                        style="max-height: min(70vh, calc(100dvh - 8rem));">
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const WHATSAPP_SETTING = @json(\App\Models\SiteSetting::get('footer_social_whatsapp', 'https://wa.me/6285959506834'));
    const VARIANTS = @json($variantOptions ?? []);

    let selectedOptions = {};
    let selectedVariant = VARIANTS[0] || null;

    function initializeVariantState() {
        if (!selectedVariant) return;

        selectedOptions = { ...selectedVariant.option_values };
        refreshVariantUI();
    }

    function selectOption(button, attribute, value) {
        if (button.disabled) return;

        selectedOptions[attribute] = value;

        const exact = findExactVariant();
        if (exact) {
            selectedVariant = exact;
        } else {
            const compatible = findFirstCompatibleVariant();
            if (compatible) {
                selectedVariant = compatible;
            }
        }

        refreshVariantUI();
    }

    function findExactVariant() {
        return VARIANTS.find((variant) => {
            const keys = Object.keys(variant.option_values || {});
            return keys.every((key) => selectedOptions[key] === variant.option_values[key]);
        }) || null;
    }

    function findFirstCompatibleVariant() {
        return VARIANTS.find((variant) => {
            return Object.entries(selectedOptions).every(([key, value]) => variant.option_values?.[key] === value);
        }) || null;
    }

    function isOptionCompatible(attribute, value) {
        return VARIANTS.some((variant) => {
            if (variant.option_values?.[attribute] !== value) {
                return false;
            }

            return Object.entries(selectedOptions).every(([key, selectedValue]) => {
                if (key === attribute) return true;
                return variant.option_values?.[key] === selectedValue;
            });
        });
    }

    function refreshVariantUI() {
        document.querySelectorAll('.variant-option-btn').forEach((btn) => {
            const attr = btn.dataset.attribute;
            const value = btn.dataset.value;

            const active = selectedOptions[attr] === value;
            const compatible = isOptionCompatible(attr, value);

            btn.disabled = !compatible;
            btn.classList.toggle('opacity-30', !compatible);
            btn.classList.toggle('cursor-not-allowed', !compatible);

            if (attr === 'color') {
                btn.classList.toggle('ring-2', active);
                btn.classList.toggle('ring-offset-2', active);
                btn.classList.toggle('ring-gray-800', active);
                btn.classList.toggle('border-gray-800', active);
            } else {
                btn.classList.toggle('border-gray-800', active);
                btn.classList.toggle('bg-gray-800', active);
                btn.classList.toggle('text-white', active);
            }
        });

        if (!selectedVariant) return;

        if (selectedVariant.price) {
            document.getElementById('product-price').textContent = selectedVariant.price;
        }

        if (selectedVariant.image) {
            setMainImage(selectedVariant.image);
        }

        if (selectedVariant.sku) {
            const skuEl = document.getElementById('product-sku');
            if (skuEl) skuEl.textContent = selectedVariant.sku;
        }
    }

    function orderOnWhatsApp() {
        const name  = document.querySelector('h1').textContent.trim();
        const price = document.getElementById('product-price').textContent.trim();
        const qty   = document.getElementById('qty').value || 1;

        const colorLabel = selectedVariant?.option_labels?.color || '-';
        const sizeLabel = selectedVariant?.option_labels?.size || '-';
        const sku = selectedVariant?.sku || document.getElementById('product-sku')?.textContent?.trim() || '-';

        const message =
            `Hi! I'd like to place an order:\n\n` +
            `🛍 *${name}*\n` +
            `🔖 SKU: ${sku}\n` +
            `💰 Price: ${price}\n` +
            `🎨 Colour: ${colorLabel}\n` +
            `📐 Size: ${sizeLabel}\n` +
            `🔢 Quantity: ${qty}\n\n` +
            `Please let me know if this is available. Thank you!`;

        let phone = (WHATSAPP_SETTING || '').trim();

        if (phone.includes('wa.me/')) {
            phone = phone.split('wa.me/')[1] || '';
        } else if (phone.includes('api.whatsapp.com/send?phone=')) {
            phone = phone.split('api.whatsapp.com/send?phone=')[1] || '';
        }

        phone = phone.split('?')[0].replace(/\D/g, '');

        if (!phone) {
            alert('WhatsApp number is not configured yet.');
            return false;
        }

        window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(message), '_blank');
        return false;
    }

    function changeQty(delta) {
        const input = document.getElementById('qty');
        const current = parseInt(input.value) || 1;
        const next = current + delta;
        if (next >= 1) input.value = next;
    }

    function showTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-gray-800', 'text-gray-800', 'font-semibold');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('content-' + tab).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-' + tab);
        activeBtn.classList.add('border-gray-800', 'text-gray-800', 'font-semibold');
        activeBtn.classList.remove('border-transparent', 'text-gray-500');
    }

    function setMainImage(src) {
        const mainImage = document.getElementById('main-image');
        if (!mainImage || !src) return;
        mainImage.src = src;
    }

    function openImagePreview(src) {
        if (!src) return;

        const modal = document.getElementById('image-preview-modal');
        const target = document.getElementById('image-preview-target');
        if (!modal || !target) return;

        target.src = src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeImagePreview(event) {
        if (event && event.target && event.target.id !== 'image-preview-modal') return;

        const modal = document.getElementById('image-preview-modal');
        const target = document.getElementById('image-preview-target');
        if (!modal || !target) return;

        modal.classList.remove('flex');
        modal.classList.add('hidden');
        target.src = '';
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeImagePreview();
        }
    });

    initializeVariantState();
</script>
@endpush
