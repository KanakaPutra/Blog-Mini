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
                    <a href="{{ route('articles.index') }}"
                        class="px-4 py-2 rounded-md text-sm font-medium
                        {{ request('filter') == 'mine'
                            ? 'bg-gray-200 text-gray-700'
                            : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                        Semua Artikel
                    </a>

                    <!-- ARTIKEL SAYA -->
                    <a href="{{ route('articles.index', ['filter' => 'mine']) }}"
                        class="px-4 py-2 rounded-md text-sm font-medium
                        {{ request('filter') == 'mine'
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Artikel Saya
                    </a>

                </div>
            @endif
        @endauth

        <!-- LIST ARTIKEL -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @foreach($articles as $article)
                <div class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-150 flex flex-col">

                    @if($article->thumbnail)
                        <img src="{{ asset('storage/'.$article->thumbnail) }}"
                            class="w-full h-48 object-cover rounded-t-lg"
                            alt="{{ $article->title }}">
                    @endif

                    <div class="p-4 flex flex-col flex-grow">

                        <h2 class="text-lg font-serif font-semibold text-gray-800 mb-1">
                            {{ $article->title }}
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
                                class="text-blue-600 hover:underline text-sm font-medium">
                                Lihat Selengkapnya
                            </a>

                            @auth
                                @if(auth()->user()->is_admin == 2 || auth()->id() == $article->user_id)
                                    <div class="flex gap-3 items-center">

                                        <a href="{{ route('articles.edit', $article->id) }}"
                                            class="text-blue-600 hover:underline text-sm font-medium">
                                            Edit
                                        </a>

                                        <form action="{{ route('articles.destroy', $article->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus artikel ini?')"
                                              class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:underline text-sm font-medium">
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
