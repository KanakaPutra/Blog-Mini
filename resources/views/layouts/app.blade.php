<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- 🏷️ Ubah nama di tab -->
    <title>The Archipelago Times</title>

    <!-- 🖼️ Tambahkan logo favicon -->
    <link rel="icon" href="{{ asset('images/kanaka-berita.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    @if(auth()->check() && auth()->user()->is_admin >= 1)
        {{-- ADMIN LAYOUT --}}
        <div class="h-screen bg-gray-100 flex overflow-hidden" x-data="{ 
                    sidebarOpen: {{ request()->cookie('sidebar_state', 'true') === 'true' ? 'true' : 'false' }}, 
                    mobileOpen: false,
                    init() {
                        this.$watch('sidebarOpen', value => {
                            document.cookie = 'sidebar_state=' + value + '; path=/; max-age=31536000; SameSite=Lax';
                        });
                    }
                }">
            <!-- Mobile Backdrop -->
            <div class="md:hidden fixed inset-0 z-40 bg-gray-600 bg-opacity-75 transition-opacity" x-show="mobileOpen"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileOpen = false"
                aria-hidden="true"></div>

            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Admin Mobile Header -->
                <div
                    class="md:hidden flex items-center justify-between h-16 bg-white border-b border-gray-200 px-4 sm:px-6">
                    <div class="flex items-center">
                        <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                    </div>
                    <button @click="mobileOpen = true"
                        class="text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    {{ $slot }}
                </main>
            </div>
        </div>
    @else
        {{-- REGULAR USER LAYOUT --}}
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    @endif
    <livewire:ai-chat />
</body>

</html>