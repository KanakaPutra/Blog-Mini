<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-4">

        {{-- Success Notification --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex items-center gap-3"
                role="alert">
                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto">
                    <svg class="h-4 w-4 text-green-700 hover:text-green-900" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Thumbnail --}}
        @if($article->thumbnail)
            <img src="{{ $article->thumbnail_url }}" class="w-full h-64 object-cover rounded mb-6 shadow">
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

        {{-- Tombol kembali + like/dislike/report --}}
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

            {{-- Like, Dislike, Report --}}
            <div class="flex items-center gap-2 order-1 md:order-2">

                @auth
                            <div x-data="{
                                                                                                            liked: {{ $article->isLikedBy(auth()->user()) ? 'true' : 'false' }},
                                                                                                            disliked: {{ $article->isDislikedBy(auth()->user()) ? 'true' : 'false' }},
                                                                                                            likesCount: {{ $article->totalLikes() }},
                                                                                                            animating: false,
                                                                                                            toggleLike() {
                                                                                                                this.animating = true;
                                                                                                                fetch('{{ route('articles.like', $article->id) }}', {
                                                                                                                    method: 'POST',
                                                                                                                    headers: {
                                                                                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                                                                        'Accept': 'application/json'
                                                                                                                    }
                                                                                                                })
                                                                                                                .then(res => res.json())
                                                                                                                .then(data => {
                                                                                                                    if (data.success) {
                                                                                                                        this.liked = data.is_liked;
                                                                                                                        this.disliked = data.is_disliked;
                                                                                                                        this.likesCount = data.total_likes;
                                                                                                                    }
                                                                                                                    setTimeout(() => this.animating = false, 500);
                                                                                                                })
                                                                                                                .catch(err => {
                                                                                                                    console.error(err);
                                                                                                                    this.animating = false;
                                                                                                                });
                                                                                                            },
                                                                                                            toggleDislike() {
                                                                                                                fetch('{{ route('articles.dislike', $article->id) }}', {
                                                                                                                    method: 'POST',
                                                                                                                    headers: {
                                                                                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                                                                        'Accept': 'application/json'
                                                                                                                    }
                                                                                                                })
                                                                                                                .then(res => res.json())
                                                                                                                .then(data => {
                                                                                                                    if (data.success) {
                                                                                                                        this.liked = data.is_liked;
                                                                                                                        this.disliked = data.is_disliked;
                                                                                                                        this.likesCount = data.total_likes;
                                                                                                                    }
                                                                                                                })
                                                                                                                .catch(err => console.error(err));
                                                                                                            }
                                                                                                        }">
                                <style>
                                    @keyframes like-bounce {
                                        0% {
                                            transform: scale(1);
                                        }

                                        40% {
                                            transform: scale(1.3) rotate(-15deg);
                                        }

                                        60% {
                                            transform: scale(1.3) rotate(15deg);
                                        }

                                        100% {
                                            transform: scale(1) rotate(0);
                                        }
                                    }

                                    .like-anim {
                                        animation: like-bounce 0.5s ease-in-out;
                                    }
                                </style>

                                {{-- LIKE --}}
                                <button @click="toggleLike()"
                                    class="group inline-flex items-center gap-2 px-4 py-2 rounded-full border transition mr-2"
                                    :class="liked 
                                                                                                                    ? 'bg-red-500 text-white border-red-600' 
                                                                                                                    : 'bg-gray-100 text-gray-600 border-gray-300 hover:bg-red-50 hover:text-red-600'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" :class="{ 
                                                                                                                        'fill-current': liked, 
                                                                                                                        'stroke-current fill-none': !liked,
                                                                                                                        'like-anim': animating 
                                                                                                                    }"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span class="font-semibold" x-text="likesCount"></span>
                                </button>

                                {{-- DISLIKE --}}
                                <button @click="toggleDislike()" class="group p-2 rounded-full transition border"
                                    :class="disliked
                                                                                                                    ? 'bg-red-500 text-white border-red-600'
                                                                                                                    : 'text-gray-500 border-gray-300 hover:text-red-600 hover:bg-red-50'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :class="disliked ? 'stroke-white' : ''"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                                    </svg>
                                </button>
                            </div>

                            {{-- REPORT --}}
                            <div x-data="{ openReportModal: false, reason: '', details: '' }">
                                <button @click="openReportModal = true" class="group p-2 rounded-full transition border
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

                                {{-- Modal --}}
                                <div x-show="openReportModal" style="display: none;"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4"
                                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6"
                                        @click.away="openReportModal = false">
                                        <h3 class="text-lg font-bold mb-4">Laporkan Artikel</h3>

                                        <form action="{{ route('articles.report', $article->id) }}" method="POST">
                                            @csrf

                                            <div class="space-y-3 mb-4">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="reason" value="Hoax" x-model="reason" required>
                                                    <span>Berita Hoax</span>
                                                </label>
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="reason" value="Menebar Kebencian" x-model="reason">
                                                    <span>Menebar Kebencian</span>
                                                </label>
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="reason" value="Sumber Gak Jelas" x-model="reason">
                                                    <span>Sumber Gak Jelas</span>
                                                </label>
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="reason" value="Lainnya" x-model="reason">
                                                    <span>Lainnya</span>
                                                </label>
                                            </div>

                                            <div x-show="reason === 'Lainnya'" class="mb-4">
                                                <textarea name="details" rows="3" class="w-full border rounded p-2"
                                                    placeholder="Jelaskan keluhan Anda..."
                                                    :required="reason === 'Lainnya'"></textarea>
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="openReportModal = false"
                                                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">Batal</button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Kirim
                                                    Laporan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                @else
                    <div class="text-sm text-gray-500">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">
                            Login
                        </a> untuk berinteraksi
                    </div>
                @endauth
            </div>
        </div>

        <hr class="my-8 border-gray-300">

        <div x-data="{
            commentContent: '',
            isSubmitting: false,
            submitComment() {
                this.isSubmitting = true;
                const formData = new FormData(this.$refs.mainForm);
                
                fetch('{{ route('comments.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        this.$refs.commentsList.insertAdjacentHTML('afterbegin', data.html);
                        this.commentContent = '';
                        if (this.$refs.noCommentsMsg) {
                            this.$refs.noCommentsMsg.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to post comment. Please try again.');
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
            }
        }">
            {{-- Form komentar --}}
            <h2 class="text-xl font-semibold mb-4">Tulis Komentar</h2>

            @auth
                <form x-ref="mainForm" @submit.prevent="submitComment" class="mb-8 space-y-3">
                    <input type="hidden" name="article_id" value="{{ $article->id }}">

                    <textarea name="content" x-model="commentContent" rows="3"
                        class="w-full border rounded p-2 focus:ring focus:ring-blue-200"
                        placeholder="Tulis komentar kamu..." required></textarea>

                    <button :disabled="isSubmitting"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition flex items-center gap-2">
                        <span x-show="!isSubmitting">Kirim</span>
                        <span x-show="isSubmitting">Sending...</span>
                    </button>
                </form>
            @else
                <p class="mb-8 text-gray-500">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                    untuk menulis komentar.
                </p>
            @endauth

            {{-- Daftar Komentar --}}
            <h2 class="text-xl font-semibold mb-4">Komentar</h2>

            <div x-ref="commentsList">
                @forelse($article->comments->where('parent_id', null)->sortByDesc('created_at')->sortByDesc('is_pinned') as $comment)
                    @include('components.comment', ['comment' => $comment])
                @empty
                    <p x-ref="noCommentsMsg" class="text-gray-500">Belum ada komentar.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>