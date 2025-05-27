<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
<script src="{{ asset('build/assets/app-4ed993c7.js') }}"></script>

   <script src="{{ asset('build/assets/app-eb687449.js') }}"></script>
   
<link rel="stylesheet" href="{{ asset('build/assets/app-49001d3f.css') }}">

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

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
