@extends('layouts.app')

@section('title', 'Shop')
@section('meta_description', 'Shop trusted products sourced from China. Browse our catalog or request custom items with our Jastip service.')
@section('meta_image', $banners[0]['image'] ?? asset('favicon.svg'))

@section('content')

    {{-- ====== Banner Carousel (DB-driven) ====== --}}
    @php
        $bannerSlides = $banners ?? [];
        $totalBanners = count($bannerSlides);
    @endphp

    @if ($totalBanners > 0)
        <div class="carousel relative container mx-auto" style="max-width:1600px;">
            <div class="carousel-inner relative overflow-hidden w-full">

                @foreach ($bannerSlides as $i => $slide)
                    @php
                        $n = $i + 1;
                        $total = $totalBanners;
                        $prevN = $n === 1 ? $total : $n - 1;
                        $nextN = $n === $total ? 1 : $n + 1;
                        $bgUrl = $slide['image'] ?? '';
                    @endphp

                    {{-- Radio control --}}
                    <input class="carousel-open" type="radio" id="carousel-{{ $n }}" name="carousel"
                        aria-hidden="true" hidden {{ $n === 1 ? 'checked' : '' }}>

                    {{-- Slide --}}
                    <div class="carousel-item absolute opacity-0" style="height:50vh;">
                        <div class="flex h-full w-full mx-auto pt-6 md:pt-0 md:items-center bg-cover bg-center"
                            @if ($bgUrl) style="background-image: url('{{ $bgUrl }}');" @endif>
                            <div class="container mx-auto">
                                <div
                                    class="flex flex-col w-full lg:w-1/2 md:ml-16 items-center md:items-start px-6 tracking-wide">
                                    @if ($slide['title'])
                                        <p class="text-black text-2xl my-4">{{ $slide['title'] }}</p>
                                    @endif
                                    @if ($slide['subtitle'])
                                        <p class="text-gray-700 text-base mb-3">{{ $slide['subtitle'] }}</p>
                                    @endif
                                    @if ($slide['link_url'])
                                        <a class="text-xl inline-block no-underline border-b border-gray-600 leading-relaxed hover:text-black hover:border-black"
                                            href="{{ $slide['link_url'] }}">{{ $slide['link_text'] }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Prev / Next labels --}}
                    <label for="carousel-{{ $prevN }}"
                        class="prev control-{{ $n }} w-10 h-10 ml-2 md:ml-10 absolute cursor-pointer hidden text-3xl font-bold text-black hover:text-white rounded-full bg-white hover:bg-gray-900 leading-tight text-center z-10 inset-y-0 left-0 my-auto">‹</label>
                    <label for="carousel-{{ $nextN }}"
                        class="next control-{{ $n }} w-10 h-10 mr-2 md:mr-10 absolute cursor-pointer hidden text-3xl font-bold text-black hover:text-white rounded-full bg-white hover:bg-gray-900 leading-tight text-center z-10 inset-y-0 right-0 my-auto">›</label>
                @endforeach

                {{-- Dot indicators --}}
                @if ($totalBanners > 1)
                    <ol class="carousel-indicators">
                        @foreach ($bannerSlides as $i => $slide)
                            <li class="inline-block mr-3">
                                <label for="carousel-{{ $i + 1 }}"
                                    class="carousel-bullet cursor-pointer block text-4xl text-gray-400 hover:text-gray-900">•</label>
                            </li>
                        @endforeach
                    </ol>
                @endif

            </div>
        </div>
    @else
        {{-- Fallback placeholder when no banners have been added yet --}}
        <div class="relative container mx-auto flex items-center justify-center bg-gray-100"
            style="max-width:1600px; height:50vh;">
            <div class="text-center px-6">
                <p class="text-gray-400 text-lg">No banners yet. Add one in the <a href="/admin/banners/create"
                        class="underline hover:text-gray-700">Admin → Banners</a>.</p>
            </div>
        </div>
    @endif

    <section class="bg-white py-8">

        <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">

            <nav id="store" class="w-full z-30 top-0 px-6 py-1">
                <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-3">

                    <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl"
                        href="{{ url('/') }}">
                        Store
                    </a>

                    <a class="text-sm text-gray-600 hover:text-black hover:underline" href="{{ url('/categories') }}">
                        Browse categories →
                    </a>
                </div>
            </nav>

            <div class="w-full px-6 mb-4">
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="filterProducts('all', this)"
                        class="category-filter-btn bg-gray-800 text-white px-4 py-2 text-sm uppercase tracking-wide">All</button>
                    @foreach ($categories as $slug => $name)
                        <button type="button" onclick="filterProducts('{{ $slug }}', this)"
                            class="category-filter-btn border border-gray-300 text-gray-700 px-4 py-2 text-sm uppercase tracking-wide hover:border-gray-800 hover:text-gray-800">{{ $name }}</button>
                    @endforeach
                </div>
            </div>

            @foreach ($products as $product)
                <div class="w-full md:w-1/3 xl:w-1/4 p-6 flex flex-col product-card"
                    data-category="{{ $product['category'] }}">
                    <a href="{{ url('/product/' . $product['slug']) }}">
                        <img class="w-full h-72 object-cover hover:grow hover:shadow-lg" src="{{ $product['image'] }}"
                            alt="{{ $product['name'] }}">
                        <div class="pt-3">
                            <p class="text-gray-800">{{ $product['name'] }}</p>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mt-1">
                                {{ $categories[$product['category']] ?? 'General' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($product['views_count'] ?? 0) }} views</p>
                        </div>
                        <p class="pt-1 text-gray-900">{{ $product['price'] }}</p>
                    </a>
                </div>
            @endforeach

        </div>

    </section>

    {{-- <section class="bg-white py-8">

        <div class="container py-8 px-6 mx-auto">

            <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl mb-8"
                href="#">
                About TGRS.CO
            </a>

            <p class="mt-8 mb-8">

                TGRS.CO helps you source products directly from trusted suppliers in China.
                Shop our curated catalog or request any item through our Jastip service.

            </p>

        </div>

    </section> --}}

@endsection

@push('scripts')
    <script>
        function filterProducts(category, button) {
            document.querySelectorAll('.product-card').forEach((card) => {
                const match = category === 'all' || card.dataset.category === category;
                card.style.display = match ? '' : 'none';
            });

            document.querySelectorAll('.category-filter-btn').forEach((btn) => {
                btn.classList.remove('bg-gray-800', 'text-white');
                btn.classList.add('border', 'border-gray-300', 'text-gray-700');
            });

            button.classList.remove('border', 'border-gray-300', 'text-gray-700');
            button.classList.add('bg-gray-800', 'text-white');
        }
    </script>
@endpush
