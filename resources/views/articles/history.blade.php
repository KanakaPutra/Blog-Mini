<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6">

        <!-- 🧭 Judul Halaman + Tabs -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between mb-8 border-b-2 border-gray-200 pb-4 gap-4">
            <h1 class="text-2xl font-semibold text-gray-800 font-serif">
                Riwayat Interaksi
            </h1>

            <div class="flex space-x-2">
                <a href="{{ route('history.like', ['type' => 'like']) }}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                   {{ $type === 'like' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Artikel Disukai
                </a>
                <a href="{{ route('history.like', ['type' => 'comment']) }}"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200
                   {{ $type === 'comment' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Artikel Dikomentari
                </a>
            </div>
        </div>

        <!-- 📚 Grid Content -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @if($type === 'like')
                @foreach($articles as $article)
                    <div
                        class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-150 flex flex-col">

                        @if($article->thumbnail)
                            <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}"
                                class="w-full h-48 object-cover rounded-t-lg">
                        @endif

                        <div class="p-4 flex flex-col flex-grow">
                            <h2 class="text-lg font-semibold text-gray-800 mb-1 font-serif">
                                {{ $article->title }}
                            </h2>

                            <p class="text-sm text-gray-500 mb-2">
                                {{ $article->category->name ?? 'Tidak ada kategori' }} •
                                {{ $article->user->name ?? 'User tidak ditemukan' }}
                            </p>

                            <p class="text-xs text-gray-400 mb-3">
                                Disukai pada {{ $article->pivot->created_at->translatedFormat('d F Y H:i') }}
                            </p>

                            <p class="text-gray-700 text-sm flex-grow">
                                {{ Str::limit(strip_tags($article->content), 120, '...') }}
                            </p>

                            <!-- LIKE + Lihat Selengkapnya -->
                            <div class="mt-4 border-t pt-3 flex items-center justify-between">

                                <a href="{{ route('articles.show', $article->id) }}"
                                    class="text-blue-600 hover:underline text-sm">
                                    Lihat Selengkapnya
                                </a>

                                <!-- Tampilkan LIKE saja -->
                                <div class="flex items-center gap-1">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 fill-current"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>

                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $article->totalLikes() }}
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif($type === 'comment')
                @foreach($comments as $comment)
                    <div
                        class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-150 flex flex-col p-6">

                        <!-- Header: Info Artikel -->
                        <div class="mb-4 pb-4 border-b border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Mengomentari Artikel:</p>
                            <h3 class="text-md font-semibold text-gray-800 font-serif leading-tight">
                                {{ $comment->article->title ?? 'Artikel dihapus' }}
                            </h3>
                        </div>

                        <!-- Isi Komentar -->
                        <div class="flex-grow">
                            <div class="bg-gray-50 p-3 rounded-md text-gray-700 text-sm italic relative">
                                <span class="absolute top-0 left-2 text-2xl text-gray-300">"</span>
                                <p class="px-2">{{ $comment->content }}</p>
                            </div>
                        </div>

                        <!-- Footer: Tanggal & Link -->
                        <div class="mt-4 pt-2 flex items-center justify-between">
                            <span class="text-xs text-gray-400">
                                {{ $comment->created_at->translatedFormat('d F Y H:i') }}
                            </span>

                            @if($comment->article)
                                <a href="{{ route('articles.show', $comment->article->id) }}#comment-{{ $comment->id }}"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                    Lihat Artikel
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-gray-400 text-sm cursor-not-allowed">Artikel dihapus</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        @if(($type === 'like' && $articles->isEmpty()) || ($type === 'comment' && $comments->isEmpty()))
            <div class="text-center py-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <p class="text-gray-500 font-serif text-lg">
                    @if($type === 'like')
                        Anda belum menyukai artikel apapun.
                    @else
                        Anda belum mengomentari artikel apapun.
                    @endif
                </p>
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline mt-2 inline-block">Jelajahi
                    Artikel</a>
            </div>
        @endif
    </div>
</x-app-layout>