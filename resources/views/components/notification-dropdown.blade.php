<div class="relative ms-3" x-data="{ openNotifications: false }" @mouseenter="openNotifications = true"
    @mouseleave="openNotifications = false">
    <button
        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 relative">
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <div x-show="openNotifications" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white border border-gray-100 rounded-md shadow-lg z-50">
        <div class="py-1">
            <div
                class="px-4 py-2 text-xs text-gray-400 uppercase tracking-widest border-b border-gray-100 flex justify-between items-center">
                <span>{{ __('Notifications') }}</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="hover:text-blue-500 transition-colors uppercase">{{ __('Mark all as read') }}</button>
                    </form>
                @endif
            </div>
            <div class="max-h-64 overflow-y-auto">
                @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                    <a href="{{ route('notifications.redirect', $notification->id) }}"
                        class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-50 last:border-0">
                        <p class="text-sm text-gray-800">{{ $notification->data['message'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </a>
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                        {{ __('No unread notifications') }}
                    </div>
                @endforelse
            </div>
            <a href="{{ route('notifications.index') }}"
                class="block px-4 py-2 text-sm text-center text-blue-600 hover:bg-gray-100 transition-colors duration-150 border-t border-gray-100">
                {{ __('View All Notifications') }}
            </a>
        </div>
    </div>
</div>