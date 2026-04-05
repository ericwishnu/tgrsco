@extends('layouts.app')

@section('title', 'Categories')
@section('meta_description', 'Browse all product categories')

@section('content')
<section class="bg-white py-12">
    <div class="container mx-auto px-6">
        <h1 class="uppercase tracking-wide font-bold text-gray-800 text-2xl mb-8">Product Categories</h1>

        <div class="flex flex-wrap -mx-4">
            @foreach($categories as $category)
                <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-8">
                    <a href="{{ url('/categories/' . $category['slug']) }}" class="block border border-gray-200 hover:border-gray-800 transition">
                        <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" class="w-full object-cover" style="height: 260px;">
                        <div class="p-5">
                            <p class="text-lg text-gray-800 font-semibold">{{ $category['name'] }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $category['count'] }} products</p>
                            <p class="text-sm text-gray-700 mt-4 uppercase tracking-wide">View collection →</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
