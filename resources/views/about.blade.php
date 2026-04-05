@extends('layouts.app')

@section('title', 'About')
@section('meta_description', 'Learn about TGRS.CO – our story, values, and the people behind the brand')

@section('content')

    {{-- Hero Banner --}}
    <section class="w-full flex items-center bg-cover bg-center"
             style="height: 40vh; background-image: url('{{ $settings['hero_image'] }}');">
        <div class="container mx-auto px-6">
            <div class="flex flex-col w-full lg:w-1/2 tracking-wide">
                <p class="text-white text-3xl font-bold mb-3" style="text-shadow: 0 1px 4px rgba(0,0,0,.4);">
                    {{ $settings['hero_title'] }}
                </p>
                <p class="text-white text-lg" style="text-shadow: 0 1px 3px rgba(0,0,0,.4);">
                    {{ $settings['hero_subtitle'] }}
                </p>
            </div>
        </div>
    </section>

    {{-- Our Story --}}
    <section class="bg-white py-16">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap -mx-6 items-center">

                <div class="w-full md:w-1/2 px-6 mb-10 md:mb-0">
                    <img src="{{ $settings['story_image'] }}"
                         alt="Our Story"
                         class="w-full object-cover hover:shadow-lg"
                         style="height: 380px;">
                </div>

                <div class="w-full md:w-1/2 px-6">
                    <h2 class="uppercase tracking-wide font-bold text-gray-800 text-xl mb-6">Our Story</h2>
                    @foreach($settings['story_paragraphs'] as $para)
                        <p class="text-gray-600 leading-relaxed mb-4">{{ $para }}</p>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    {{-- Values --}}
    <section class="bg-gray-50 py-16 border-t border-b border-gray-200">
        <div class="container mx-auto px-6">
            <h2 class="uppercase tracking-wide font-bold text-gray-800 text-xl mb-3 text-center">What We Stand For</h2>
            @if(!empty($settings['values_subtitle']))
            <p class="text-gray-500 text-sm text-center max-w-2xl mx-auto mb-12 leading-relaxed">
                {{ $settings['values_subtitle'] }}
            </p>
            @else
            <div class="mb-12"></div>
            @endif
            <div class="flex flex-wrap -mx-6">

                @php
                    $valueIcons = [
                        // globe / sustainability
                        'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z',
                        // shield / quality
                        'M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l6 2.67V11c0 3.88-2.67 7.52-6 8.93-3.33-1.41-6-5.05-6-8.93V7.67L12 5z',
                        // group / makers
                        'M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z',
                    ];
                    $values = [
                        ['title' => $settings['value_1_title'], 'body' => $settings['value_1_body'], 'icon' => $valueIcons[0]],
                        ['title' => $settings['value_2_title'], 'body' => $settings['value_2_body'], 'icon' => $valueIcons[1]],
                        ['title' => $settings['value_3_title'], 'body' => $settings['value_3_body'], 'icon' => $valueIcons[2]],
                    ];
                @endphp

                @foreach($values as $loop_i => $value)
                <div class="w-full md:w-1/3 px-6 mb-10 {{ !$loop->last ? 'md:mb-0' : '' }} text-center">
                    <div class="flex justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-700 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="{{ $value['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-3">{{ $value['title'] }}</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">{{ $value['body'] }}</p>
                </div>
                @endforeach

            </div>
        </div>
    </section>

    {{-- Jastip — How It Works --}}
    <section class="bg-white py-16 border-t border-gray-200">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="uppercase tracking-wide font-bold text-gray-800 text-xl mb-3">
                    {{ $settings['jastip_title'] }}
                </h2>
                <p class="text-gray-500 max-w-xl mx-auto text-sm leading-relaxed">
                    {{ $settings['jastip_subtitle'] }}
                </p>
            </div>

            <div class="flex flex-wrap -mx-6">

                <div class="w-full md:w-1/3 px-6 mb-10 md:mb-0">
                    <div class="flex items-start">
                        <span class="text-5xl font-bold text-gray-100 mr-4 leading-none select-none">01</span>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">{{ $settings['jastip_step_1_title'] }}</h3>
                            <p class="text-gray-600 leading-relaxed text-sm">{{ $settings['jastip_step_1_body'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/3 px-6 mb-10 md:mb-0">
                    <div class="flex items-start">
                        <span class="text-5xl font-bold text-gray-100 mr-4 leading-none select-none">02</span>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">{{ $settings['jastip_step_2_title'] }}</h3>
                            <p class="text-gray-600 leading-relaxed text-sm">{{ $settings['jastip_step_2_body'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/3 px-6 mb-10 md:mb-0">
                    <div class="flex items-start">
                        <span class="text-5xl font-bold text-gray-100 mr-4 leading-none select-none">03</span>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">{{ $settings['jastip_step_3_title'] }}</h3>
                            <p class="text-gray-600 leading-relaxed text-sm">{{ $settings['jastip_step_3_body'] }}</p>
                        </div>
                    </div>
                </div>

            </div>

            @if(!empty($settings['jastip_whatsapp']))
            <div class="text-center mt-12">
                <a href="{{ $settings['jastip_whatsapp'] }}" target="_blank" rel="noopener"
                   class="inline-block bg-green-500 hover:bg-green-600 text-white px-8 py-3 text-sm uppercase tracking-widest font-semibold">
                    Order via WhatsApp
                </a>
            </div>
            @endif
        </div>
    </section>

    {{-- Team --}}
    @if(count($settings['team_members']) > 0)
    <section class="bg-white py-16">
        <div class="container mx-auto px-6">
            <h2 class="uppercase tracking-wide font-bold text-gray-800 text-xl mb-12 text-center">The Team</h2>
            <div class="flex flex-wrap justify-center -mx-6">

                @foreach($settings['team_members'] as $member)
                <div class="w-full sm:w-1/2 md:w-1/3 px-6 mb-10 text-center">
                    @if(!empty($member['image']))
                        <img src="{{ $member['image'] }}"
                             alt="{{ $member['name'] ?? '' }}"
                             class="w-32 h-32 rounded-full object-cover mx-auto mb-4 hover:shadow-lg">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-200 mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                        </div>
                    @endif
                    <h3 class="font-bold text-gray-800">{{ $member['name'] ?? '' }}</h3>
                    <p class="text-gray-500 text-sm mt-1">{{ $member['role'] ?? '' }}</p>
                </div>
                @endforeach

            </div>
        </div>
    </section>
    @endif

    {{-- Newsletter --}}
    <section class="bg-gray-50 py-16 border-t border-gray-200">
        <div class="container mx-auto px-6 text-center">
            <h2 class="uppercase tracking-wide font-bold text-gray-800 text-xl mb-3">Stay in the Loop</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Sign up for our newsletter and be the first to hear about new arrivals, stories from our makers, and exclusive offers.</p>
            <form class="flex flex-col sm:flex-row justify-center gap-3 max-w-lg mx-auto">
                <input type="email" placeholder="Your email address"
                    class="flex-1 border border-gray-300 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-gray-800">
                <button type="submit"
                    class="bg-gray-800 text-white px-6 py-3 text-sm uppercase tracking-widest hover:bg-black focus:outline-none">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

@endsection
