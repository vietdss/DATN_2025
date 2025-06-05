<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'ShareCycle - Chia sẻ thực phẩm và đồ dùng, giảm lãng phí')</title>
    <meta name="description" content="@yield('description', 'Nền tảng chia sẻ thực phẩm và đồ dùng hàng đầu Việt Nam. Kết nối cộng đồng, giảm lãng phí, bảo vệ môi trường.')">
    <meta name="keywords" content="@yield('keywords', 'chia sẻ thực phẩm, đồ dùng miễn phí, giảm lãng phí, bảo vệ môi trường, cộng đồng')">
    <meta name="author" content="ShareCycle">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', request()->url())">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('og_title', 'ShareCycle - Chia sẻ thực phẩm và đồ dùng')">
    <meta property="og:description" content="@yield('og_description', 'Nền tảng chia sẻ thực phẩm và đồ dùng hàng đầu Việt Nam')">
    <meta property="og:image" content="@yield('og_image', asset('images/sharecycle-og.jpg'))">
    <meta property="og:url" content="@yield('og_url', request()->url())">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="ShareCycle">
    <meta property="og:locale" content="vi_VN">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'ShareCycle - Chia sẻ thực phẩm và đồ dùng')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Nền tảng chia sẻ thực phẩm và đồ dùng hàng đầu Việt Nam')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/sharecycle-twitter.jpg'))">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Structured Data -->
    @stack('structured-data')

    <!-- Styles & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('build/assets/app-4ed993c7.js') }}"></script>
    <script src="{{ asset('build/assets/app-7789bbe6.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('build/assets/app-49001d3f.css') }}">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) -->

</head>

<body class="bg-gray-50 min-h-screen flex flex-col" style="overflow: auto;">
    {{-- Header --}}
    @include('components.header')

    {{-- Nội dung chính --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')
    @stack('scripts')
</body>
</html>
