<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MyGlucoTrack') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <div class="w-64 min-h-screen bg-white shadow-lg border-r border-gray-100 flex flex-col">
        @include('layouts.sidebar')
    </div>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col">

        {{-- TOP BAR --}}
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-700">
                @yield('page-title', 'MyGlucoTrack')
            </h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-500 hover:text-blue-600 transition flex items-center gap-2">
                    {{ auth()->user()->fullname }}
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ auth()->user()->role === 'System God' ? 'bg-purple-100 text-purple-700' :
                           (auth()->user()->role === 'Admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ auth()->user()->role }}
                    </span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-500 hover:text-red-700 font-medium transition">Logout</button>
                </form>
            </div>
        </header>

        {{-- FLASH --}}
        @if(session('success'))
            <div class="mx-6 mt-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm">❌ {{ session('error') }}</div>
        @endif

        <main class="flex-1 p-6">@yield('content')</main>

        <footer class="text-center text-xs text-gray-400 py-4 border-t">
            © {{ date('Y') }} MyGlucoTrack — Health Monitoring System
        </footer>

    </div>
</div>

</body>
</html>
