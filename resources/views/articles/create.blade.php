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
        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            @csrf

            <input type="text" name="title" placeholder="Judul Artikel"
                   class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">

            <select name="category_id"
                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <input type="file" name="thumbnail"
                   class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm">

            <textarea name="content" rows="5" placeholder="Isi artikel..."
                      class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md p-2 text-sm"></textarea>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition">
                Simpan
            </button>
        </form>
    </div>
</x-app-layout>
