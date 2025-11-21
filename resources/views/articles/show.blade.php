<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-4">

        {{-- Thumbnail --}}
        @if($article->thumbnail)
            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-full h-64 object-cover rounded mb-6 shadow">
        @endif

        {{-- Judul --}}
        <h1 class="text-3xl font-serif font-semibold text-gray-900 mb-3">
            {{ $article->title }}
        </h1>

        {{-- Info Artikel --}}
        <p class="text-sm text-gray-500 mb-6">
            Kategori: <strong>{{ $article->category->name ?? 'Tidak ada' }}</strong> •
            Penulis: <strong>{{ $article->user->name ?? 'Tidak ditemukan' }}</strong> •
            <span class="text-gray-400">{{ $article->created_at->translatedFormat('d F Y') }}</span>
        </p>

        {{-- Konten Artikel --}}
        <div class="prose prose-blue max-w-none text-justify leading-relaxed mb-10">
            {!! nl2br(e($article->content)) !!}
        </div>

        {{-- Action Buttons & Back Link --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-10">

            {{-- Tombol Kembali --}}
            <a href="{{ route('dashboard') }}"
                class="text-gray-500 hover:text-gray-700 font-medium transition flex items-center gap-2 order-2 md:order-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Kembali ke Beranda
            </a>

            <div class="flex items-center gap-2 order-1 md:order-2">

                @auth

                    {{-- LIKE --}}
                    <form action="{{ route('articles.like', $article->id) }}" method="POST">
                        @csrf
                        <button
                            class="group flex items-center gap-2 px-4 py-2 rounded-full border transition
                            {{ $article->isLikedBy(auth()->user()) 
                                ? 'bg-red-500 text-white border-red-600' 
                                : 'bg-gray-100 text-gray-600 border-gray-300 hover:bg-red-50 hover:text-red-600' }}">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 {{ $article->isLikedBy(auth()->user()) ? 'fill-current' : 'stroke-current fill-none' }}"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>

                            <span class="font-semibold">{{ $article->totalLikes() }}</span>
                        </button>
                    </form>

                    {{-- DISLIKE --}}
                    <form action="{{ route('articles.dislike', $article->id) }}" method="POST">
                        @csrf
                        <button
                            class="group p-2 rounded-full transition border
                            {{ $article->isDislikedBy(auth()->user())
                                ? 'bg-red-500 text-white border-red-600'
                                : 'text-gray-500 border-gray-300 hover:text-red-600 hover:bg-red-50' }}">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 {{ $article->isDislikedBy(auth()->user()) ? 'stroke-white' : '' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                            </svg>
                        </button>
                    </form>

                    {{-- REPORT --}}
                    <form action="{{ route('articles.report', $article->id) }}" method="POST">
                        @csrf
                        <button
                            class="group p-2 rounded-full transition border
                            {{ $article->isReportedBy(auth()->user())
                                ? 'bg-yellow-400 text-black border-yellow-500'
                                : 'text-gray-500 border-gray-300 hover:text-yellow-600 hover:bg-yellow-50' }}">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 {{ $article->isReportedBy(auth()->user()) ? 'stroke-black' : '' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </button>
                    </form>

                @else
                    <div class="text-sm text-gray-500">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Login</a> untuk
                        berinteraksi
                    </div>
                @endauth
            </div>

        </div>

        <hr class="my-8 border-gray-300">

        {{-- ========================= --}}
        {{-- === Form Komentar === --}}
        {{-- ========================= --}}
        <h2 class="text-xl font-semibold mb-4">Tulis Komentar</h2>

        @auth
            <form action="{{ route('comments.store') }}" method="POST" class="mb-8 space-y-3">
                @csrf
                <input type="hidden" name="article_id" value="{{ $article->id }}">

                <textarea name="content" rows="3" class="w-full border rounded p-2 focus:ring focus:ring-blue-200"
                    placeholder="Tulis komentar kamu..." required></textarea>

                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Kirim
                </button>
            </form>
        @else
            <p class="mb-8 text-gray-500">
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                    Login
                </a>
                untuk menulis komentar.
            </p>
        @endauth

        {{-- ========================= --}}
        {{-- === Daftar Komentar === --}}
        {{-- ========================= --}}
        <h2 class="text-xl font-semibold mb-4">Komentar Lainnya</h2>

        @forelse($article->comments->sortByDesc('created_at') as $comment)
            <div class="border rounded p-3 mb-3 bg-gray-50 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">
                    <strong>{{ $comment->user->name ?? 'User hilang' }}</strong>
                    <span class="text-gray-400">· {{ $comment->created_at->diffForHumans() }}</span>
                </p>
                <p class="text-gray-700">
                    {{ $comment->content }}
                </p>
            </div>
        @empty
            <p class="text-gray-500">Belum ada komentar.</p>
        @endforelse

    </div>
</x-app-layout>
