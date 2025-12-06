<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6 font-sans">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-serif font-semibold text-gray-800">
                Daftar Artikel
            </h1>

            @auth
                @if(auth()->user()->is_admin >= 1)
                    <a href="{{ route('articles.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                        + Tambah Artikel
                    </a>
                @endif
            @endauth
        </div>

        <!-- FILTER UNTUK SUPER ADMIN -->
        @auth
            @if(auth()->user()->is_admin == 2)
                <div class="mb-8 flex items-center gap-3">

                    <!-- ALL ARTIKEL -->
                    <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded-md text-sm font-medium
                                {{ request('filter') == 'mine'
                    ? 'bg-gray-200 text-gray-700'
                    : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                        Semua Artikel
                    </a>

                    <!-- ARTIKEL SAYA -->
                    <a href="{{ route('articles.index', ['filter' => 'mine']) }}" class="px-4 py-2 rounded-md text-sm font-medium
                                {{ request('filter') == 'mine'
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Artikel Saya
                    </a>

                </div>
            @endif
        @endauth

        <!-- LIST ARTIKEL -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">

            @foreach($articles as $article)
                <div x-show="loaded"
                     style="transition-delay: {{ $loop->index * 100 }}ms"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 transform translate-y-8"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col transform">

                    @if($article->thumbnail)
                        <div class="relative overflow-hidden rounded-t-lg">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}"
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

                    @if($article->suspended)
                        <div class="bg-red-50 border-l-4 border-red-500 p-3 mx-4 mt-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-red-700">
                                        Artikel ini melanggar aturan dan sedang ditangguhkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="p-4 flex flex-col flex-grow">

                        <h2 class="text-lg font-serif font-semibold text-gray-800 mb-1 hover:text-blue-600 transition-colors">
                            <a href="{{ route('articles.show', $article->id) }}">
                                {{ $article->title }}
                            </a>
                        </h2>

                        <!-- HANDLE NULL CATEGORY & USER -->
                        <p class="text-sm text-gray-500 mb-1">
                            {{ $article->category->name ?? 'Tidak ada kategori' }}
                            •
                            {{ $article->user->name ?? 'User tidak ditemukan' }}
                        </p>

                        <p class="text-xs text-gray-400 mb-3">
                            Dipublikasikan pada {{ $article->created_at->translatedFormat('d F Y') }}
                        </p>

                        <p class="text-gray-700 text-sm flex-grow leading-relaxed">
                            {{ Str::limit(strip_tags($article->content), 120, '...') }}
                        </p>

                        <div class="mt-4 flex justify-between items-center border-t pt-3">

                            <a href="{{ route('articles.show', $article->id) }}"
                                class="text-blue-600 hover:underline text-sm font-medium group flex items-center gap-1">
                                Lihat Selengkapnya
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>

                            @auth
                                @if(auth()->user()->is_admin == 2 || auth()->id() == $article->user_id)
                                    <div class="flex gap-3 items-center">

                                        <a href="{{ route('articles.edit', $article->id) }}"
                                            class="text-blue-600 hover:underline text-sm font-medium">
                                            Edit
                                        </a>

                                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus artikel ini?')" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm font-medium">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                @endif
                            @endauth

                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        @if($articles->isEmpty())
            <p class="text-gray-500 mt-10 text-center font-serif">
                Belum ada artikel.
            </p>
        @endif

    </div>
</x-app-layout>