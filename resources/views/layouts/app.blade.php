<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $seoTitle = trim($__env->yieldContent('title', 'Shop')) . ' | TGRS.CO';
        $seoDescription = trim($__env->yieldContent('meta_description', 'Shop trusted products sourced from China with TGRS.CO, including catalog orders and Jastip requests.'));
        $seoUrl = trim($__env->yieldContent('canonical_url', url()->current()));
        $seoImage = trim($__env->yieldContent('meta_image', asset('favicon.svg')));
        $seoRobots = trim($__env->yieldContent('meta_robots', 'index,follow'));
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TGRS.CO">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,400&display=swap" rel="stylesheet">

    <style>
        .work-sans {
            font-family: 'Work Sans', sans-serif;
        }

        #menu-toggle:checked + #menu {
            display: block;
        }

        .hover\:grow {
            transition: all 0.3s;
            transform: scale(1);
        }

        .hover\:grow:hover {
            transform: scale(1.02);
        }

        .carousel-open:checked + .carousel-item {
            position: static;
            opacity: 100;
        }

        .carousel-item {
            -webkit-transition: opacity 0.6s ease-out;
            transition: opacity 0.6s ease-out;
        }

        #carousel-1:checked ~ .control-1,
        #carousel-2:checked ~ .control-2,
        #carousel-3:checked ~ .control-3 {
            display: block;
        }

        .carousel-indicators {
            list-style: none;
            margin: 0;
            padding: 0;
            position: absolute;
            bottom: 2%;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 10;
        }

        #carousel-1:checked ~ .control-1 ~ .carousel-indicators li:nth-child(1) .carousel-bullet,
        #carousel-2:checked ~ .control-2 ~ .carousel-indicators li:nth-child(2) .carousel-bullet,
        #carousel-3:checked ~ .control-3 ~ .carousel-indicators li:nth-child(3) .carousel-bullet {
            color: #000;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-white text-gray-600 work-sans leading-normal text-base tracking-normal">

    <!--Nav-->
    <nav id="header" class="w-full z-30 top-0 py-1">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-6 py-3">

            <label for="menu-toggle" class="cursor-pointer md:hidden block">
                <svg class="fill-current text-gray-900" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                    <title>menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                </svg>
            </label>
            <input class="hidden" type="checkbox" id="menu-toggle" />

            <div class="hidden md:flex md:items-center md:w-auto w-full order-3 md:order-2" id="menu">
                <nav>
                    <ul class="md:flex items-center justify-between text-base text-gray-700 pt-4 md:pt-0">
                        <li><a class="inline-block no-underline hover:text-black hover:underline py-2 px-4 {{ request()->is('/') ? 'text-black font-semibold' : '' }}" href="{{ url('/') }}">Shop</a></li>
                        <li><a class="inline-block no-underline hover:text-black hover:underline py-2 px-4 {{ request()->is('categories*') ? 'text-black font-semibold' : '' }}" href="{{ url('/categories') }}">Categories</a></li>
                        <li><a class="inline-block no-underline hover:text-black hover:underline py-2 px-4 {{ request()->is('about') ? 'text-black font-semibold' : '' }}" href="{{ url('/about') }}">About</a></li>
                    </ul>
                </nav>
            </div>

            <div class="order-1 md:order-1">
                <a class="flex items-center tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl" href="{{ url('/') }}">
                    <svg class="fill-current text-gray-800 mr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M5,22h14c1.103,0,2-0.897,2-2V9c0-0.553-0.447-1-1-1h-3V7c0-2.757-2.243-5-5-5S7,4.243,7,7v1H4C3.447,8,3,8.447,3,9v11 C3,21.103,3.897,22,5,22z M9,7c0-1.654,1.346-3,3-3s3,1.346,3,3v1H9V7z M5,10h2v2h2v-2h6v2h2v-2h2l0.002,10H5V10z" />
                    </svg>
                    TGRS.CO
                </a>
            </div>

            @if(isset($activeCurrencies) && $activeCurrencies->count())
                <div class="order-2 md:order-3 w-full md:w-auto mt-3 md:mt-0">
                    <form method="POST" action="{{ route('currency.set', ['code' => $currentCurrency->code ?? $activeCurrencies->first()->code]) }}">
                        @csrf
                        <label for="currency" class="sr-only">Currency</label>
                        <select id="currency" name="currency" onchange="if (this.value) { this.form.action = '{{ url('/currency') }}/' + this.value; this.form.submit(); }" class="border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-gray-800">
                            @foreach($activeCurrencies as $currency)
                                <option value="{{ $currency->code }}" {{ ($currentCurrency->code ?? '') === $currency->code ? 'selected' : '' }}>
                                    {{ $currency->code }} ({{ $currency->symbol }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif


        </div>
    </nav>

    {{-- Page content --}}
    @yield('content')

    {{-- Footer --}}
    @php
        $footerAbout   = \App\Models\SiteSetting::get('footer_about', 'A curated collection of homeware and everyday objects inspired by Nordic minimalism.');
        $socialWhatsapp = \App\Models\SiteSetting::get('footer_social_whatsapp', '');
        $socialTwitter  = \App\Models\SiteSetting::get('footer_social_twitter',  '');
        $socialFacebook = \App\Models\SiteSetting::get('footer_social_facebook', '');
        $socialInstagram= \App\Models\SiteSetting::get('footer_social_instagram','');
        $socialPinterest= \App\Models\SiteSetting::get('footer_social_pinterest','');
    @endphp
    <footer class="container mx-auto bg-white py-8 border-t border-gray-400">
        <div class="container flex px-3 py-8">
            <div class="w-full mx-auto flex flex-wrap">
                <div class="flex w-full lg:w-1/2">
                    <div class="px-3 md:px-0">
                        <h3 class="font-bold text-gray-900">About</h3>
                        <p class="py-4">{{ $footerAbout }}</p>
                    </div>
                </div>
                <div class="flex w-full lg:w-1/2 lg:justify-end lg:text-right mt-6 md:mt-0">
                    <div class="px-3 md:px-0">
                        <h3 class="text-left font-bold text-gray-900">Social</h3>
                        <div class="w-full flex items-center py-4 mt-0">
                            @if($socialTwitter)
                            <a href="{{ $socialTwitter }}" target="_blank" rel="noopener" class="mx-2" aria-label="Twitter">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            @endif
                            @if($socialFacebook)
                            <a href="{{ $socialFacebook }}" target="_blank" rel="noopener" class="mx-2" aria-label="Facebook">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            @endif
                            @if($socialInstagram)
                            <a href="{{ $socialInstagram }}" target="_blank" rel="noopener" class="mx-2" aria-label="Instagram">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                </svg>
                            </a>
                            @endif
                            @if($socialPinterest)
                            <a href="{{ $socialPinterest }}" target="_blank" rel="noopener" class="mx-2" aria-label="Pinterest">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @if($socialWhatsapp)
        <a href="{{ $socialWhatsapp }}"
            target="_blank"
            rel="noopener"
            aria-label="Chat on WhatsApp"
            title="Chat on WhatsApp"
            class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-green-500 hover:bg-green-600 text-white shadow-lg flex items-center justify-center transition-transform duration-200 hover:scale-105">
            <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M20.52 3.48A11.85 11.85 0 0 0 12.07 0C5.5 0 .16 5.34.16 11.91c0 2.1.55 4.16 1.6 5.98L0 24l6.27-1.64a11.9 11.9 0 0 0 5.79 1.48h.01c6.57 0 11.91-5.34 11.92-11.91a11.8 11.8 0 0 0-3.47-8.45zm-8.45 18.35h-.01a9.86 9.86 0 0 1-5.03-1.38l-.36-.21-3.72.98 1-3.62-.23-.37a9.85 9.85 0 0 1-1.51-5.23C2.21 6.48 6.55 2.14 12.07 2.14a9.76 9.76 0 0 1 6.96 2.89 9.77 9.77 0 0 1 2.88 6.97c0 5.52-4.34 9.83-9.84 9.83zm5.4-7.37c-.29-.15-1.71-.84-1.98-.94-.27-.1-.46-.15-.65.14-.19.29-.75.94-.92 1.13-.17.19-.34.22-.63.07-.29-.15-1.21-.45-2.3-1.44-.85-.75-1.42-1.68-1.59-1.97-.17-.29-.02-.45.13-.6.13-.13.29-.34.44-.51.15-.17.19-.29.29-.48.1-.19.05-.36-.02-.51-.07-.15-.65-1.57-.89-2.15-.24-.57-.49-.49-.65-.49h-.56c-.19 0-.51.07-.78.36-.27.29-1.02 1-1.02 2.43s1.05 2.82 1.19 3.01c.15.19 2.06 3.15 4.99 4.41.7.3 1.24.48 1.66.62.7.22 1.34.19 1.84.11.56-.08 1.71-.7 1.95-1.38.24-.68.24-1.26.17-1.38-.07-.12-.27-.19-.56-.34z"/>
            </svg>
        </a>
    @endif

    @stack('scripts')

</body>

</html>
