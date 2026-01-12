<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6 font-sans">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-semibold text-gray-800">
                Artikel Kategori:
                <span class="text-blue-600 italic">{{ $category->name }}</span>
            </h1>
        </div>

        <!-- Grid Artikel -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($articles as $article)
                @if(!$article->suspended)
                    <div
                        class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-150 flex flex-col">

                        @if($article->thumbnail)
                            <img src="{{ $article->thumbnail_url }}" class="w-full h-48 object-cover rounded-t-lg"
                                alt="{{ $article->title }}">
                        @endif

                        <div class="p-4 flex flex-col flex-grow">
                            <h2 class="text-lg font-serif font-semibold text-gray-800 mb-1">
                                {{ $article->title }}
                            </h2>
                            <p class="text-sm text-gray-500 mb-1">
                                {{ $article->category->name }} • {{ $article->user->name }}
                            </p>
                            <p class="text-xs text-gray-400 mb-3">
                                Dipublikasikan pada {{ $article->created_at->translatedFormat('d F Y') }}
                            </p>

                            <p class="text-gray-700 text-sm flex-grow leading-relaxed">
                                {{ Str::limit(strip_tags($article->content), 120, '...') }}
                            </p>

                            <div class="mt-4 flex justify-between items-center border-t pt-3" x-data="{
                                                bookmarked: @auth {{ $article->isBookmarkedBy(auth()->user()) ? 'true' : 'false' }} @else false @endauth,
                                                toggleBookmark() {
                                                    @auth
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
                                                            }
                                                        })
                                                        .catch(err => console.error(err));
                                                    @else
                                                        window.location.href = '{{ route('login') }}';
                                                    @endauth
                                                }
                                            }">
                                <a href="{{ route('articles.show', $article->id) }}"
                                    class="text-blue-600 hover:underline text-sm font-medium">
                                    Lihat Selengkapnya →
                                </a>

                                <div class="flex items-center gap-2">
                                    <button @click="toggleBookmark()" class="transition-colors duration-200"
                                        :class="bookmarked ? 'text-blue-500' : 'text-gray-400 hover:text-blue-500'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                            :class="bookmarked ? 'fill-current' : 'fill-none'" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <p class="text-gray-500 mt-10 font-serif text-center">
                    Belum ada artikel untuk kategori ini.
                </p>
            @endforelse
        </div>
    </div>
</x-app-layout>