@php /** @var \App\Models\User $authenticatedUser */ $authenticatedUser = auth()->user(); @endphp
<div :class="[
        sidebarOpen ? 'md:w-72' : 'md:w-[5.5rem]',
        mobileOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
    ]" x-init="$el.classList.remove('initial-w-64', 'initial-w-20')"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white/80 backdrop-blur-xl border-r border-gray-100 min-h-screen flex flex-col transition-all duration-500 cubic-bezier(0.4, 0, 0.2, 1) md:relative md:inset-auto md:flex shadow-xl md:shadow-none {{ request()->cookie('sidebar_state', 'true') === 'true' ? 'initial-w-64' : 'initial-w-20' }}">

    <!-- Logo Area -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100/50">
        <a href="{{ route('dashboard') }}" x-show="sidebarOpen || mobileOpen" class="md:block overflow-hidden" x-cloak
            :class="{'hidden': !sidebarOpen && !mobileOpen}"
            x-transition:enter="delay-200 transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 -translate-x-2">
            <x-application-logo class="block h-10 w-auto fill-current text-gray-800" />
        </a>

        <!-- Desktop Toggle Button -->
        <button @click="sidebarOpen = !sidebarOpen"
            class="hidden md:flex items-center justify-center p-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:outline-none transition-all duration-300"
            :class="{ 'ml-auto': sidebarOpen, 'mx-auto': !sidebarOpen }">
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': !sidebarOpen}"
                class="h-5 w-5 transform transition-transform duration-500 ease-in-out" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Mobile Close Button -->
        <button @click="mobileOpen = false"
            class="md:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors duration-200">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto overflow-x-hidden no-scrollbar"
        x-data="{ openIndex: false }">

        <!-- Search Section -->
        <div class="px-2 mb-6">
            <div class="relative group">
                <!-- Search Button (when sidebar closed) -->
                <button @click="sidebarOpen = true; $nextTick(() => $refs.sidebarSearch.focus())" x-show="!sidebarOpen"
                    class="w-full flex items-center justify-center p-3 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-blue-600 transition-all duration-300 group-hover:scale-110"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Search Form (when sidebar open) -->
                <form action="{{ route('articles.index') }}" method="GET" x-show="sidebarOpen" x-cloak
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" class="relative group">

                    <input x-ref="sidebarSearch" type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('Search articles...') }}"
                        class="block w-full pl-4 pr-11 py-2.5 text-sm rounded-2xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 shadow-sm border border-transparent focus:border-blue-500/50">

                    <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-blue-600 hover:bg-white rounded-lg transition-all duration-200 focus:outline-none active:scale-95">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>

                <!-- Tooltip for closed state -->
                <div x-show="!sidebarOpen" x-cloak
                    class="absolute left-full top-1/2 transform -translate-y-1/2 ml-4 px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-lg opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-xl">
                    {{ __('Search') }}
                    <div class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 w-2 h-2 bg-gray-900 rotate-45">
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-300 ease-out group
           {{ request()->routeIs('dashboard')
    ? 'bg-blue-50/80 text-blue-600 shadow-sm ring-1 ring-blue-100'
    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
            <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center transition-colors duration-300">
                <svg class="h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span class="ml-3 whitespace-nowrap font-medium" x-show="sidebarOpen || mobileOpen" x-cloak
                x-transition:enter="delay-100 transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">{{ __('Dashboard') }}</span>

            <!-- Tooltip -->
            <div class="absolute left-full top-1/2 transform -translate-y-1/2 ml-4 px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-lg opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-xl"
                x-show="!sidebarOpen && !mobileOpen">
                {{ __('Dashboard') }}
                <div class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 w-2 h-2 bg-gray-900 rotate-45">
                </div>
            </div>
        </a>

        <!-- Index Dropdown -->
        <div class="relative" x-data="{ hoverIndex: false }" @mouseenter="hoverIndex = true"
            @mouseleave="hoverIndex = false">
            <button @click="openIndex = !openIndex"
                class="w-full flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-300 ease-out group"
                :class="openIndex ? 'bg-gray-50 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1'">
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-600 transition-colors duration-300"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="flex-1 flex items-center justify-between ml-3 whitespace-nowrap overflow-hidden"
                    x-show="sidebarOpen || mobileOpen" x-cloak
                    x-transition:enter="delay-100 transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <span class="font-medium">{{ __('Index') }}</span>
                    <svg :class="{ 'rotate-180': openIndex }"
                        class="h-4 w-4 text-gray-400 transform transition-transform duration-300"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </button>

            <!-- Expanded Mode Submenu (Accordion) -->
            <div x-show="openIndex && (sidebarOpen || mobileOpen)" x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2 h-0"
                x-transition:enter-end="opacity-100 translate-y-0 h-auto"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 h-auto"
                x-transition:leave-end="opacity-0 -translate-y-2 h-0" class="mt-1 space-y-1 pl-11 overflow-hidden">
                <div class="relative border-l-2 border-gray-100 pl-2 ml-1 py-1 space-y-1">
                    @foreach (\App\Models\Category::all() as $category)
                                    <a href="{{ route('category.show', $category->id) }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200
                                                                                                                                                           {{ request()->is('category/' . $category->id)
                        ? 'bg-blue-50 text-blue-600 font-semibold'
                        : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 hover:pl-4' }}">
                                        {{ $category->name }}
                                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Collapsed Mode Submenu (Floating) -->
            <div x-show="!sidebarOpen && !mobileOpen && hoverIndex" x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2 scale-95"
                x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                x-transition:leave-end="opacity-0 translate-x-2 scale-95"
                class="absolute left-full top-0 ml-4 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                <div
                    class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                    {{ __('Index') }}
                </div>
                <div class="max-h-[calc(100vh-10rem)] overflow-y-auto custom-scrollbar p-1">
                    @foreach (\App\Models\Category::all() as $category)
                        <a href="{{ route('category.show', $category->id) }}"
                            class="block px-4 py-2.5 text-sm rounded-lg transition-colors duration-150
                                                        {{ request()->is('category/' . $category->id) ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-8 mb-4 px-4" x-tmp>
            <div class="h-px bg-gray-100 w-full"></div>
        </div>

        <div class="px-2" x-show="sidebarOpen || mobileOpen" x-cloak
            x-transition:enter="delay-100 transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">
                {{ __('Admin Menu') }}
            </div>
        </div>

        @if(auth()->user()->is_admin >= 1)
            <a href="{{ route('articles.index') }}" class="relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-300 ease-out group
                                                           {{ request()->routeIs('articles.index')
            ? 'bg-blue-50/80 text-blue-600 shadow-sm ring-1 ring-blue-100'
            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <svg class="h-5 w-5 {{ request()->routeIs('articles.index') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap font-medium" x-show="sidebarOpen || mobileOpen" x-cloak
                    x-transition:enter="delay-100 transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">{{ __('Articles') }}</span>

                <!-- Tooltip -->
                <div class="absolute left-full top-1/2 transform -translate-y-1/2 ml-4 px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-lg opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-xl"
                    x-show="!sidebarOpen && !mobileOpen">
                    {{ __('Articles') }}
                    <div class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 w-2 h-2 bg-gray-900 rotate-45">
                    </div>
                </div>
            </a>

            <!-- Bookmarks for Admin -->
            <a href="{{ route('bookmarks.index') }}" class="relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-300 ease-out group
                                                           {{ request()->routeIs('bookmarks.index')
            ? 'bg-blue-50/80 text-blue-600 shadow-sm ring-1 ring-blue-100'
            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <svg class="h-5 w-5 {{ request()->routeIs('bookmarks.index') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap font-medium" x-show="sidebarOpen || mobileOpen" x-cloak
                    x-transition:enter="delay-100 transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">{{ __('Bookmarks') }}</span>

                <!-- Tooltip -->
                <div class="absolute left-full top-1/2 transform -translate-y-1/2 ml-4 px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-lg opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-xl"
                    x-show="!sidebarOpen && !mobileOpen">
                    {{ __('Bookmarks') }}
                    <div class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 w-2 h-2 bg-gray-900 rotate-45">
                    </div>
                </div>
            </a>
        @endif

        @if(auth()->user()->is_admin == 2)
            <div class="px-2 mt-6" x-show="sidebarOpen || mobileOpen" x-cloak
                x-transition:enter="delay-100 transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">
                    {{ __('Super Admin') }}
                </div>
            </div>

            <a href="{{ route('superadmin.users') }}" class="relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-300 ease-out group
                                                           {{ request()->routeIs('superadmin.users')
            ? 'bg-blue-50/80 text-blue-600 shadow-sm ring-1 ring-blue-100'
            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <svg class="h-5 w-5 {{ request()->routeIs('superadmin.users') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap font-medium" x-show="sidebarOpen || mobileOpen" x-cloak
                    x-transition:enter="delay-100 transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">{{ __('Manage Users') }}</span>

                <!-- Tooltip -->
                <div class="absolute left-full top-1/2 transform -translate-y-1/2 ml-4 px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-lg opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-xl"
                    x-show="!sidebarOpen && !mobileOpen">
                    {{ __('Manage Users') }}
                    <div class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 w-2 h-2 bg-gray-900 rotate-45">
                    </div>
                </div>
            </a>

            <a href="{{ route('superadmin.settings') }}" class="relative flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-300 ease-out group
                                                           {{ request()->routeIs('superadmin.settings')
            ? 'bg-blue-50/80 text-blue-600 shadow-sm ring-1 ring-blue-100'
            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <svg class="h-5 w-5 {{ request()->routeIs('superadmin.settings') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap font-medium" x-show="sidebarOpen || mobileOpen" x-cloak
                    x-transition:enter="delay-100 transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">{{ __('Settings') }}</span>

                <!-- Tooltip -->
                <div class="absolute left-full top-1/2 transform -translate-y-1/2 ml-4 px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-lg opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-xl"
                    x-show="!sidebarOpen && !mobileOpen">
                    {{ __('Settings') }}
                    <div class="absolute left-0 top-1/2 -translate-x-1 -translate-y-1/2 w-2 h-2 bg-gray-900 rotate-45">
                    </div>
                </div>
            </a>
        @endif
    </nav>

    <!-- User Profile & Logout (Bottom) -->
    <div class="border-t border-gray-100/50 p-4 bg-gray-50/50 backdrop-blur-sm relative"
        x-data="{ openProfile: false }">
        <!-- Floating Menu -->
        <div x-show="openProfile" x-cloak x-transition:enter="transition cubic-bezier(0.4, 0, 0.2, 1) duration-300"
            x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition cubic-bezier(0.4, 0, 0.2, 1) duration-200"
            x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
            :class="sidebarOpen ? 'bottom-full left-0 w-full mb-3 px-4' : 'bottom-0 left-full ml-4 w-60'"
            class="absolute z-50">
            <div
                class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden py-1.5 ring-1 ring-gray-900/5">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $authenticatedUser->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $authenticatedUser->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200 group">
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Profile') }}
                </a>

                <a href="{{ route('history.like') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200 group">
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Riwayat Like') }}
                </a>

                <a href="{{ route('bookmarks.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200 group">
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    {{ __('Bookmarks') }}
                </a>

                <a href="{{ route('notifications.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200 group">
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    {{ __('Riwayat Notifikasi') }}
                </a>

                <div class="h-px bg-gray-100 my-1"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200 group">
                        <svg class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-500 transition-colors"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>

        <button @click="openProfile = !openProfile"
            class="flex items-center justify-between w-full focus:outline-none group p-2 rounded-xl hover:bg-white hover:shadow-sm transition-all duration-300">
            <div class="flex items-center overflow-hidden">
                <!-- User Avatar / Initials (Always Visible) -->
                <div
                    class="flex-shrink-0 w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-md ring-2 ring-white">
                    {{ substr($authenticatedUser->name, 0, 1) }}
                </div>
                <div class="ml-3 text-left whitespace-nowrap overflow-hidden" x-show="sidebarOpen || mobileOpen" x-cloak
                    x-transition:enter="delay-100 transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <div class="font-bold text-sm text-gray-800 leading-tight">{{ $authenticatedUser->name }}</div>
                    <div class="font-normal text-xs text-gray-500 truncate max-w-[9rem]">{{ $authenticatedUser->email }}
                    </div>
                </div>
            </div>
            <svg :class="{ 'rotate-180': openProfile }" x-show="sidebarOpen || mobileOpen"
                class="h-5 w-5 text-gray-400 transform transition-transform duration-300 group-hover:text-gray-600"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>
</div>