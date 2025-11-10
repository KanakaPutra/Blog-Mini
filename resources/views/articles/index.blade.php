<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-semibold text-gray-800">Daftar Artikel</h1>

            @auth
                <a href="{{ route('articles.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                   + Tambah Artikel
                </a>
            @endauth
        </div>

        <!-- Grid Artikel -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($articles as $article)
                <div class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-150 flex flex-col">
                    @if($article->thumbnail)
                        <img src="{{ asset('storage/'.$article->thumbnail) }}" 
                             alt="{{ $article->title }}" 
                             class="w-full h-48 object-cover rounded-t-lg">
                    @endif

                    <div class="p-4 flex flex-col flex-grow">
                        <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ $article->title }}</h2>
                        <p class="text-sm text-gray-500 mb-2">
                            {{ $article->category->name }} • {{ $article->user->name }}
                        </p>
                        <p class="text-gray-700 text-sm flex-grow">{{ Str::limit($article->content, 120) }}</p>

                        <div class="mt-4 flex justify-between items-center border-t pt-3">
                            <a href="{{ route('articles.show', $article->id) }}" 
                               class="text-blue-600 hover:underline text-sm">Lihat Selengkapnya</a>

                            @auth
                                <div class="flex gap-3">
                                    <a href="{{ route('articles.edit', $article->id) }}" 
                                       class="text-blue-600 hover:underline text-sm">Edit</a>
                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" 
                                          onsubmit="return confirm('Yakin hapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline text-sm">Hapus</button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($articles->isEmpty())
            <p class="text-gray-500 mt-10">Belum ada artikel.</p>
        @endif
    </div>
</x-app-layout>
