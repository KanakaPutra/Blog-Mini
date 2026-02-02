@php /** @var \App\Models\User $authenticatedUser */ $authenticatedUser = auth()->user(); @endphp
<nav x-data="navComponent()" class="bg-white border-b border-gray-100" x-cloak>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @unless(auth()->check() && auth()->user()->is_admin >= 1)
                            <a href="{{ route('dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                            <!-- Dashboard -->
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>

                            <!-- Index Dropdown -->
                            <div class="relative" @mouseenter="openIndex = true" @mouseleave="openIndex = false">
                                <button
                                    class="inline-flex items-center px-3 py-2 border-b-2 text-sm font-medium focus:outline-none transition ease-in-out duration-150
                                                                                                                                        {{ request()->routeIs('category.show') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-700 hover:text-gray-900 hover:border-gray-300' }}">
                                    {{ __('Index') }}
                                    <svg :class="{ 'rotate-180': openIndex }"
                                        class="ms-1 h-4 w-4 transform transition-transform duration-200"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="openIndex" x-cloak x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute top-full left-0 mt-0 w-48 bg-white border border-gray-100 rounded-md shadow-lg z-50">
                                    <div class="py-1">
                                        @foreach ($categories as $category)
                                            <a href="{{ route('category.show', $category->id) }}"
                                                class="block px-4 py-2 text-sm rounded-sm transition-colors duration-150
                                                                                                                                                                                            {{ request()->is('category/' . $category->id) ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                    @endunless


                </div>

                <!-- Search Bar -->
                <div class="hidden sm:flex items-center ms-6 flex-1 max-w-md">
                    <form action="{{ route('search.index') }}" method="GET" class="w-full relative group">
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="{{ __('Search') }}"
                            class="block w-full pl-4 pr-11 py-2 text-sm border-gray-200 rounded-2xl leading-5 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 shadow-sm hover:border-gray-300">

                        <button type="submit"
                            class="absolute right-1.5 top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 focus:outline-none active:scale-95">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if (Auth::check())
                    <!-- Notifications Dropdown -->
                    <x-notification-dropdown />

                    <div class="relative ms-3" @mouseenter="openProfile = true" @mouseleave="openProfile = false">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ $authenticatedUser->name }}</div>
                            <svg :class="{ 'rotate-180': openProfile }"
                                class="ms-1 h-4 w-4 transform transition-transform duration-200"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="openProfile" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-md shadow-lg z-50">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    {{ __('Profile') }}
                                </a>
                                <a href="{{ route('history.like') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    {{ __('Riwayat Like') }}
                                </a>
                                <a href="{{ route('bookmarks.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    {{ __('Bookmarks') }}
                                </a>
                                <a href="{{ route('notifications.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                    {{ __('Riwayat Notifikasi') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-800">{{ __('Register') }}</a>
                    </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <!-- Notifications Bell for Mobile -->
                @if (Auth::check())
                    <x-notification-dropdown />
                @endif

                <button @click="open = !open"
                    class="ml-2 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden" x-cloak>
        <!-- Mobile Search -->
        <div class="pt-4 pb-2 px-4 border-b border-gray-100 bg-gray-50/50">
            <form action="{{ route('search.index') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="{{ __('Search') }}"
                    class="block w-full pl-4 pr-11 py-2 text-sm border-gray-200 rounded-2xl bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 shadow-sm">

                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-blue-600 hover:bg-gray-100 rounded-lg transition-all duration-200 focus:outline-none active:scale-95">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            @unless(auth()->check() && auth()->user()->is_admin >= 1)
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endunless
            <!-- Index Menu -->
            @unless(auth()->check() && auth()->user()->is_admin >= 1)
                @if (isset($categories))
                    <div class="border-t border-gray-200 mt-2"></div>
                    <div class="px-4 pt-2">
                        <p class="text-sm text-gray-600 font-semibold mb-2">{{ __('Index') }}</p>
                        @foreach ($categories as $category)
                            <a href="{{ route('category.show', $category->id) }}"
                                class="block px-3 py-1.5 text-sm rounded-md 
                                                                                                                                                                                                   {{ request()->is('category/' . $category->id) ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endunless


        </div>

        <!-- Responsive User Menu -->
        @if (Auth::check())
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ $authenticatedUser->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $authenticatedUser->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('history.like')">
                        {{ __('Riwayat Like') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('bookmarks.index')">
                        {{ __('Bookmarks') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('notifications.index')">
                        {{ __('Riwayat Notifikasi') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-200 px-4">
                <a href="{{ route('login') }}" class="block text-gray-600 hover:text-gray-800">{{ __('Login') }}</a>
                <a href="{{ route('register') }}"
                    class="block text-gray-600 hover:text-gray-800 mt-2">{{ __('Register') }}</a>
            </div>
        @endif
    </div>
</nav>

<!-- Alpine component -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navComponent', () => ({
            open: false,
            openIndex: false,
            openProfile: false,
            openNotifications: false,
        }))
    })
</script>

<!-- Pastikan di CSS global -->
<style>
    [x-cloak] {
        display: none !important
    }
</style>