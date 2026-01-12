<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6">

        <!-- 🧭 Judul Halaman -->
        <div class="mb-8 border-b-2 border-gray-200 pb-4">
            <h1 class="text-2xl font-semibold text-gray-800 font-serif">
                Daftar Simpanan (Bookmarks)
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Kumpulan artikel yang Anda simpan untuk dibaca nanti.
            </p>
        </div>

        <!-- 📚 Grid Content -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($bookmarks as $article)
                <div
                    class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-150 flex flex-col">

                    @if($article->thumbnail)
                        <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}"
                            class="w-full h-48 object-cover rounded-t-lg">
                    @endif

                    <div class="p-4 flex flex-col flex-grow">
                        <h2 class="text-lg font-semibold text-gray-800 mb-1 font-serif leading-tight">
                            {{ $article->title }}
                        </h2>

                        <p class="text-sm text-gray-500 mb-2">
                            {{ $article->category->name ?? 'Tidak ada kategori' }} •
                            {{ $article->user->name ?? 'User tidak ditemukan' }}
                        </p>

                        <p class="text-gray-700 text-sm flex-grow line-clamp-3">
                            {{ Str::limit(strip_tags($article->content), 120, '...') }}
                        </p>

                        <!-- Actions -->
                        <div class="mt-4 border-t pt-3 flex items-center justify-between">
                            <a href="{{ route('articles.show', $article->id) }}"
                                class="text-blue-600 hover:underline text-sm font-medium">
                                Baca Selengkapnya
                            </a>

                            <div x-data="{
                                        bookmarked: true,
                                        toggleBookmark() {
                                            fetch('{{ route('articles.bookmark', $article->id) }}', {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Accept': 'application/json',
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                }
                                            })
                                            .then(res => res.json())
                                            .then(data => {
                                                if (data.success) {
                                                    this.bookmarked = data.is_bookmarked;
                                                    if (!this.bookmarked) {
                                                        // Secara opsional bisa hapus element dari DOM tanpa refresh
                                                        // Tapi untuk simplisitas biarkan saja atau refresh halaman
                                                        window.location.reload();
                                                    }
                                                }
                                            })
                                            .catch(err => console.error(err));
                                        }
                                    }">
                                <button @click="toggleBookmark()" class="text-red-500 hover:text-red-700 p-1"
                                    title="Hapus dari simpanan">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 bg-gray-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    <p class="text-gray-500 font-serif text-lg">
                        Anda belum menyimpan artikel apapun.
                    </p>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline mt-2 inline-block font-medium">
                        Jelajahi Artikel
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $bookmarks->links() }}
        </div>
    </div>
</x-app-layout>