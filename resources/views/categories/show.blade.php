@extends('layouts.app')

@section('title', $categoryName)
@section('meta_description', 'Browse ' . $categoryName . ' products sourced from trusted China suppliers via TGRS.CO.')

@section('content')
<section class="bg-white py-10 border-b border-gray-200">
    <div class="container mx-auto px-6">
        <nav class="text-sm text-gray-500 mb-3">
            <a href="{{ url('/') }}" class="hover:text-black hover:underline">Shop</a>
            <span class="mx-2">/</span>
            <a href="{{ url('/categories') }}" class="hover:text-black hover:underline">Categories</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800">{{ $categoryName }}</span>
        </nav>

        <h1 class="uppercase tracking-wide font-bold text-gray-800 text-2xl">{{ $categoryName }}</h1>
        <p class="text-gray-500 mt-2">{{ count($products) }} products in this category</p>
    </div>
</section>

<section class="bg-white py-8">
    <div class="container mx-auto px-6">

        <div class="w-full mb-6">
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $slug => $name)
                    <a href="{{ url('/categories/' . $slug) }}"
                        class="px-4 py-2 text-sm uppercase tracking-wide border {{ $slug === $categorySlug ? 'bg-gray-800 text-white border-gray-800' : 'border-gray-300 text-gray-700 hover:border-gray-800 hover:text-gray-800' }}">
                        {{ $name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="flex flex-wrap -mx-4">
            @forelse($products as $product)
                <div class="w-full md:w-1/3 xl:w-1/4 px-4 mb-8">
                    <a href="{{ url('/product/' . $product['slug']) }}" class="block">
                        <img class="hover:grow hover:shadow-lg w-full" src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                        <div class="pt-3">
                            <p class="text-gray-800">{{ $product['name'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($product['views_count'] ?? 0) }} views</p>
                        </div>
                        @if($product['has_price'] ?? false)
                            <p class="pt-1 text-gray-900">{{ $product['price'] }}</p>
                        @endif
                    </a>
                </div>
            @empty
                <div class="w-full">
                    <p class="text-gray-500">No products available for this category yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
