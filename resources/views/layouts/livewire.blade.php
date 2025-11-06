<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased bg-gray-50 text-gray-900">
<div class="min-h-screen">
    {{-- Simple top bar; you can remove if you like --}}
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="/" class="font-semibold">Shelter Paws</a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('animals.index') }}">Animals</a>
                <a href="{{ route('adoption.queue') }}">Adoption Queue</a>
                <a href="/hello-inertia">Hello Inertia</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-600">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="py-8">
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>
