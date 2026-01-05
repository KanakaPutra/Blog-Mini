<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Notifikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <div class="mb-4 flex justify-end">
                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm transition-colors duration-150">
                                    {{ __('Tandai Semua sebagai Dibaca') }}
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="space-y-4">
                        @forelse($notifications as $notification)
                            <div
                                class="p-4 border rounded-lg {{ $notification->read_at ? 'bg-gray-50' : 'bg-white border-blue-200' }} flex justify-between items-center hover:shadow-sm transition-shadow duration-150">
                                <a href="{{ route('notifications.redirect', $notification->id) }}" class="flex-1">
                                    <p
                                        class="text-sm font-medium {{ $notification->read_at ? 'text-gray-600' : 'text-gray-900' }}">
                                        {{ $notification->data['message'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </a>
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                                        class="ml-4">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs text-blue-600 hover:text-blue-800 font-semibold uppercase whitespace-nowrap">
                                            {{ __('Tandai Dibaca') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                {{ __('Belum ada notifikasi.') }}
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>