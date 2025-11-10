<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-4">
        {{-- Thumbnail --}}
        @if($article->thumbnail)
            <img src="{{ asset('storage/'.$article->thumbnail) }}" class="w-full h-64 object-cover rounded mb-4">
        @endif

        {{-- Judul & Info --}}
        <h1 class="text-3xl font-semibold mb-2">{{ $article->title }}</h1>
        <p class="text-sm text-gray-500 mb-4">
            Kategori: {{ $article->category->name }} | Penulis: {{ $article->user->name }}
        </p>

        {{-- Konten --}}
        <div class="text-gray-800 leading-relaxed mb-6 text-justify prose prose-blue max-w-none">
            {!! nl2br(e($article->content)) !!}
        </div>

        <a href="{{ route('dashboard') }}" class="inline-block mb-6 text-blue-600 hover:underline">
            ← Kembali ke Daftar Artikel
        </a>


        <hr class="my-6 border-gray-300">

        {{-- === Bagian Komentar === --}}
        <h2 class="text-xl font-semibold mb-4">Tulis Komentar</h2>

        {{-- Form komentar --}}
        @auth
            <form action="{{ route('comments.store') }}" method="POST" class="mb-8 space-y-3">
                @csrf
                <input type="hidden" name="article_id" value="{{ $article->id }}">
                <textarea name="content" rows="3" class="w-full border rounded p-2" placeholder="Tulis komentar kamu..."></textarea>
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Kirim
                </button>
            </form>
        @else
            <p class="mb-8 text-gray-500">
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> untuk menulis komentar.
            </p>
        @endauth

        <h2 class="text-xl font-semibold mb-4">Komentar Lainnya</h2>

        {{-- Daftar komentar --}}
        @forelse($article->comments->sortByDesc('created_at') as $comment)
            <div class="border rounded p-3 mb-3 bg-gray-50">
                <p class="text-sm text-gray-600 mb-1">
                    <strong>{{ $comment->user->name }}</strong> ·
                    <span class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                </p>
                <p class="text-gray-700">{{ $comment->content }}</p>
            </div>
        @empty
            <p class="text-gray-500">Belum ada komentar.</p>
        @endforelse
    </div>
</x-app-layout>
