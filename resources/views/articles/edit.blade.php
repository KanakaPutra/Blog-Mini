<x-app-layout>
    <div class="max-w-2xl mx-auto py-10 px-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Artikel</h1>
            <a href="{{ route('articles.index') }}"
                class="text-gray-600 hover:text-gray-800 border border-gray-300 px-3 py-1.5 rounded-md text-sm transition">
                ← Kembali
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-4 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            @csrf
            @method('PUT')

            <input type="text" name="title" value="{{ $article->title }}"
                class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">

            <!-- Kategori (opsional) -->
            <div x-data="{ 
                categoryId: '{{ old('category_id', $article->category_id) }}', 
                open: false, 
                get categoryName() {
                    if (!this.categoryId) return '-- Pilih Kategori (Opsional) --';
                    @foreach($categories as $category)
                        if (this.categoryId == '{{ $category->id }}') return '{{ addslashes($category->name) }}';
                    @endforeach
                    return '-- Pilih Kategori (Opsional) --';
                }
            }" class="space-y-1">
                <input type="hidden" name="category_id" x-model="categoryId">

                <div class="relative">
                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="inline-flex w-full justify-between items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm text-gray-900 border border-gray-300 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <span x-text="categoryName" class="truncate"></span>
                        <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute z-20 mt-1 w-full rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none max-h-60 overflow-y-auto"
                        style="display: none;">
                        <div class="py-1">
                            <a href="#" @click.prevent="categoryId = ''; open = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                :class="{ 'bg-gray-100 text-gray-900': !categoryId }">
                                -- Pilih Kategori (Opsional) --
                            </a>
                            @foreach($categories as $category)
                                <a href="#" @click.prevent="categoryId = '{{ $category->id }}'; open = false"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                    :class="{ 'bg-gray-100 text-gray-900': categoryId == '{{ $category->id }}' }">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @if($article->thumbnail)
                <div>
                    <img src="{{ $article->thumbnail_url }}" class="w-full h-48 object-cover rounded mb-2 border">
                </div>
            @endif

            <input type="file" name="thumbnail"
                class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">

            <textarea name="content" rows="5"
                class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">{{ $article->content }}</textarea>

            <!-- Status & Schedule -->
            <div x-data="{ 
                mode: '{{ old('status', $article->status) == 'draft' ? 'draft' : ((old('published_at', $article->published_at) && \Carbon\Carbon::parse(old('published_at', $article->published_at))->isFuture()) ? 'schedule' : 'now') }}',
            }" class="space-y-4 pt-4 border-t border-gray-100">

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Status Publikasi</label>

                    <!-- Custom Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" @click.outside="open = false"
                            class="inline-flex w-full justify-between items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <span x-text="
                                mode === 'now' ? 'Publish Now' : 
                                (mode === 'schedule' ? 'Scheduled' : 'Draft')
                            "></span>
                            <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute bottom-full right-0 z-10 mb-2 w-full origin-bottom-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            style="display: none;">
                            <div class="py-1">
                                <a href="#" @click.prevent="mode = 'now'; open = false"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                    :class="{ 'bg-gray-100 text-gray-900': mode === 'now' }">
                                    Publish Now
                                </a>
                                <a href="#" @click.prevent="mode = 'schedule'; open = false"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                    :class="{ 'bg-gray-100 text-gray-900': mode === 'schedule' }">
                                    Scheduled
                                </a>
                                <a href="#" @click.prevent="mode = 'draft'; open = false"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 text-left w-full"
                                    :class="{ 'bg-gray-100 text-gray-900': mode === 'draft' }">
                                    Draft
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs for backend compatibility -->
                <input type="hidden" name="status" :value="mode === 'draft' ? 'draft' : 'published'">

                <!-- Date Picker for Schedule -->
                <div x-show="mode === 'schedule'" x-transition
                    class="bg-blue-50 p-4 rounded-lg border border-blue-100 space-y-2">
                    <label class="block text-sm font-medium text-blue-800">Pilih Waktu Tayang</label>
                    <input type="datetime-local" name="published_at"
                        value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full border-blue-200 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm text-blue-900 placeholder-blue-300">
                    <p class="text-xs text-blue-600">Artikel akan otomatis terbit pada waktu yang dipilih.</p>
                </div>
            </div>

            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium text-sm transition">
                Perbarui Artikel
            </button>
        </form>
    </div>
</x-app-layout>