<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-semibold mb-6">Beranda Artikel</h1>

        <div class="grid md:grid-cols-2 gap-6">
            @foreach($articles as $article)
                <div class="bg-white border rounded-lg shadow-sm p-5">
                    @if($article->thumbnail)
                        <img src="{{ asset('storage/'.$article->thumbnail) }}" 
                             class="w-full h-48 object-cover rounded mb-3">
                    @endif

                    <h2 class="text-xl font-semibold mb-1">{{ $article->title }}</h2>
                    <p class="text-gray-500 text-sm mb-3">
                        {{ $article->category->name }} | {{ $article->user->name }}
                    </p>

                    <p class="text-gray-700 mb-4">
                        {{ Str::limit(strip_tags($article->content), 120, '...') }}
                    </p>
                    
                    <a href="{{ route('articles.show', $article->id) }}" 
                    class="text-blue-600 hover:underline">Lihat Selengkapnya →</a>

                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
