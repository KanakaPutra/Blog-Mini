<nav 
    x-data="{ open: false, openIndex: false }" 
    class="bg-white border-b border-gray-100"
>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
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
                    <div class="relative" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                        <button 
                            @click="openIndex = !openIndex"
                            class="inline-flex items-center px-3 py-2 border-b-2 text-sm leading-4 font-medium focus:outline-none transition ease-in-out duration-150
                                {{ request()->routeIs('category.show') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-700 hover:text-gray-900 hover:border-gray-300' }}"
                        >
                            {{ __('Index') }}
                            <svg 
                                :class="{ 'rotate-180': openIndex }"
                                class="ms-1 h-4 w-4 transform transition-transform duration-200"
                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown List -->
                        <div 
                            x-show="openIndex" 
                            x-transition 
                            @click.away="openIndex = false"
                            class="absolute top-full left-0 mt-0 w-48 bg-white border border-gray-100 rounded-md shadow-lg z-50"
                        >
                            @foreach ($categories as $category)
                                <a 
                                    href="{{ route('category.show', $category->id) }}"
                                    @click="openIndex = false"
                                    class="block px-4 py-2 text-sm rounded-sm
                                        {{ request()->is('category/'.$category->id) 
                                            ? 'bg-blue-50 text-blue-600 font-semibold' 
                                            : 'text-gray-700 hover:bg-gray-100' }}"
                                >
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Articles (Admin Only) -->
                    @auth
                        @if (auth()->user()->is_admin)
                            <x-nav-link :href="route('articles.index')" :active="request()->routeIs('articles.*')">
                                {{ __('Articles') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6" x-data="{ openProfile: false }">
                @if (Auth::check())
                    <div class="relative">
                        <button @click="openProfile = !openProfile"
                            class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <svg :class="{ 'rotate-180': openProfile }"
                                class="ms-1 h-4 w-4 transform transition-transform duration-200"
                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-show="openProfile" x-transition @click.away="openProfile = false"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-md shadow-lg z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Profile') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-800">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Responsive Index Menu -->
            @if (isset($categories))
                <div class="border-t border-gray-200 mt-2"></div>
                <div class="px-4 pt-2">
                    <p class="text-sm text-gray-600 font-semibold mb-2">{{ __('Index') }}</p>
                    @foreach ($categories as $category)
                        <a href="{{ route('category.show', $category->id) }}"
                            class="block px-3 py-1.5 text-sm rounded-md 
                                {{ request()->is('category/'.$category->id) 
                                    ? 'bg-blue-50 text-blue-600 font-semibold' 
                                    : 'text-gray-700 hover:bg-gray-100' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            @auth
                @if (auth()->user()->is_admin)
                    <x-responsive-nav-link :href="route('articles.index')" :active="request()->routeIs('articles.*')">
                        {{ __('Articles') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive User Menu -->
        @if (Auth::check())
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
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
                <a href="{{ route('login') }}" class="block text-gray-600 hover:text-gray-800">
                    {{ __('Login') }}
                </a>
                <a href="{{ route('register') }}" class="block text-gray-600 hover:text-gray-800 mt-2">
                    {{ __('Register') }}
                </a>
            </div>
        @endif
    </div>
</nav>
