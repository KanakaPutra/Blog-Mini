<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6 font-sans">

        <!-- Header with Tabs -->
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-semibold text-gray-800 mb-6">
                Hasil Pencarian: "{{ $query }}"
            </h1>

            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'all']) }}"
                        class="{{ $type === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                              whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        All Results
                    </a>

                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'articles']) }}"
                        class="{{ $type === 'articles' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                              whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Articles
                    </a>

                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'users']) }}"
                        class="{{ $type === 'users' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                              whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Users
                    </a>
                </nav>
            </div>
        </div>

        @if(
                ($type === 'all' && $users->isEmpty() && $articles->isEmpty()) ||
                ($type === 'users' && $users->isEmpty()) ||
                ($type === 'articles' && $articles->isEmpty())
            )
            <div class="text-center py-20 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                <p class="text-gray-500 text-lg font-serif">Tidak ada hasil ditemukan untuk filter ini.</p>
                @if($type !== 'all')
                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'all']) }}"
                        class="mt-2 inline-block text-blue-600 hover:underline text-sm">Lihat semua hasil</a>
                @endif
            </div>
        @else

            {{-- USERS SECTION --}}
            @if($users->isNotEmpty())
                <div class="mb-12">
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($users as $user)
                            <div
                                class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-all duration-300 p-5 flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-lg font-bold text-gray-600 border border-gray-200">
                                        {{ $user->initials() }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $user->email }}
                                    </p>
                                    @if($user->is_admin)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Penulis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ARTICLES SECTION --}}
            @if($articles->isNotEmpty())
                <div>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" x-data="{ loaded: false }"
                        x-init="setTimeout(() => loaded = true, 100)">
                        @foreach($articles as $article)
                            <div x-show="loaded" style="transition-delay: {{ $loop->index * 50 }}ms"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 transform translate-y-8"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col transform">

                                @if($article->thumbnail)
                                    <div class="relative overflow-hidden rounded-t-lg">
                                        <img src="{{ $article->thumbnail_url }}"
                                            class="w-full h-48 object-cover transition-transform duration-500 hover:scale-110 {{ $article->suspended ? 'opacity-50' : '' }}"
                                            alt="{{ $article->title }}">

                                        @if($article->suspended)
                                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                <span
                                                    class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                                                    Ditangguhkan
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="p-4 flex flex-col flex-grow">
                                    <h2
                                        class="text-lg font-serif font-semibold text-gray-800 mb-1 hover:text-blue-600 transition-colors">
                                        <a href="{{ route('articles.show', $article->id) }}">
                                            {{ $article->title }}
                                        </a>
                                    </h2>

                                    <p class="text-sm text-gray-500 mb-1">
                                        {{ $article->category->name ?? 'Tidak ada kategori' }} •
                                        {{ $article->user->name ?? 'User tidak ditemukan' }}
                                    </p>

                                    <p class="text-xs text-gray-400 mb-3">
                                        {{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : $article->created_at->translatedFormat('d F Y') }}
                                    </p>

                                    <p class="text-gray-700 text-sm flex-grow leading-relaxed">
                                        {{ Str::limit(strip_tags($article->content), 120, '...') }}
                                    </p>

                                    <div class="mt-4 flex justify-between items-center border-t pt-3">
                                        <a href="{{ route('articles.show', $article->id) }}"
                                            class="text-blue-600 hover:underline text-sm font-medium group flex items-center gap-1">
                                            Lihat Selengkapnya
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 transform transition-transform group-hover:translate-x-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endif

    </div>
</x-app-layout>