<div class="flex items-center gap-5" x-data="{
    bookmarked: {{ $article->isBookmarkedBy(auth()->user()) ? 'true' : 'false' }},
    likesCount: {{ $article->totalLikes() }},
    isLiked: {{ auth()->check() && $article->isLikedBy(auth()->user()) ? 'true' : 'false' }},
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
            }
        })
        .catch(err => console.error(err));
    }
}">
    <!-- 🔖 Minimal Bookmark -->
    @auth
        <button @click.prevent="toggleBookmark()" 
                class="transition-all duration-300 transform active:scale-95 group/bookmark flex items-center gap-1.5"
                aria-label="Toggle Bookmark">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px] transition-all duration-300"
                :class="bookmarked ? 'fill-blue-700 text-blue-700' : 'fill-none text-gray-500 group-hover/bookmark:text-blue-700'" 
                viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
        </button>
    @endauth

    <!-- 🤍 Minimal Like -->
    <div class="flex items-center gap-1.5 group/like cursor-default">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px] transition-all duration-300 transform group-hover/like:scale-110" 
            :class="isLiked ? 'text-rose-600 fill-current' : 'text-gray-500 stroke-current fill-none group-hover/like:text-rose-600'" 
            viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <span class="text-[11px] font-black text-gray-600 group-hover/like:text-gray-900 transition-colors uppercase tracking-widest" x-text="likesCount">
            {{ $article->totalLikes() }}
        </span>
    </div>
</div>