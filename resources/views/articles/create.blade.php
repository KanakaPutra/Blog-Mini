<x-app-layout>
    <div class="max-w-2xl mx-auto py-10 px-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Tambah Artikel</h1>
            <a href="{{ route('articles.index') }}"
               class="text-gray-600 hover:text-gray-800 border border-gray-300 px-3 py-1.5 rounded-md text-sm transition">
                ← Kembali
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data"
              class="space-y-4 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            @csrf

            <!-- Judul -->
            <input type="text" name="title" value="{{ old('title') }}"
                   placeholder="Judul Artikel"
                   class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">
            @error('title')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <!-- Kategori (opsional) -->
            <select name="category_id"
                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">
                <option value="">-- Pilih Kategori (Opsional) --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <!-- Thumbnail -->
            <input type="file" name="thumbnail"
                   class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">
            @error('thumbnail')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <!-- Konten -->
            <textarea name="content" rows="5" placeholder="Isi artikel..."
                      class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">{{ old('content') }}</textarea>
            @error('content')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <!-- Tombol submit -->
            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition">
                Simpan
            </button>
        </form>
    </div>
</x-app-layout>
