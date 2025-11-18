<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-6">

        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Super Admin Settings</h1>
        <p class="text-gray-700 mb-6">Di sini kamu bisa menambahkan pengaturan khusus Super Admin.</p>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Statistik User --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white shadow rounded p-4 text-center">
                <h2 class="text-gray-600">Total Users</h2>
                <p class="text-xl font-bold">{{ $userCount }}</p>
            </div>
            <div class="bg-white shadow rounded p-4 text-center">
                <h2 class="text-gray-600">Total Admin</h2>
                <p class="text-xl font-bold">{{ $adminCount }}</p>
            </div>
            <div class="bg-white shadow rounded p-4 text-center">
                <h2 class="text-gray-600">Total Super Admin</h2>
                <p class="text-xl font-bold">{{ $superAdminCount }}</p>
            </div>
        </div>

        {{-- Diagram --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white shadow rounded p-4">
                <h2 class="text-gray-700 text-sm mb-2 text-center">Artikel per User</h2>
                <canvas id="articlesPerUserChart" style="max-height:200px;"></canvas>
            </div>
            <div class="bg-white shadow rounded p-4">
                <h2 class="text-gray-700 text-sm mb-2 text-center">Distribusi Role User</h2>
                <canvas id="userRoleChart" style="max-height:200px;"></canvas>
            </div>
        </div>

        {{-- Form Tambah Kategori --}}
        <form action="{{ route('categories.store') }}" method="POST" class="mb-6">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="name" placeholder="Nama Kategori"
                       class="border rounded p-2 w-full" required>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah</button>
            </div>
            @error('name')
                <p class="text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </form>

        {{-- Tabel Kategori --}}
        <table class="min-w-full bg-white border rounded">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 border text-center">ID</th>
                    <th class="py-2 px-4 border">Nama Kategori</th>
                    <th class="py-2 px-4 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td class="py-2 px-4 border text-center">{{ $category->id }}</td>
                        <td class="py-2 px-4 border">
                            <form action="{{ route('categories.update', $category->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" class="border rounded p-1 w-full"
                                       value="{{ $category->name }}" required>
                                <button type="submit" class="bg-yellow-400 px-2 py-1 rounded text-white">
                                    Ubah
                                </button>
                            </form>
                        </td>
                        <td class="py-2 px-4 border text-center">
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 px-2 py-1 rounded text-white">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Artikel per User
        const articlesPerUserCtx = document.getElementById('articlesPerUserChart').getContext('2d');
        new Chart(articlesPerUserCtx, {
            type: 'bar',
            data: {
                labels: @json($articlePerUser->keys()),
                datasets: [{
                    label: 'Jumlah Artikel',
                    data: @json($articlePerUser->values()),
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, precision: 0 }
                }
            }
        });

        // Chart User Role Distribution
        const userRoleCtx = document.getElementById('userRoleChart').getContext('2d');
        new Chart(userRoleCtx, {
            type: 'doughnut',
            data: {
                labels: ['User', 'Admin', 'Super Admin'],
                datasets: [{
                    label: 'Jumlah User',
                    data: [{{ $normalUserCount }}, {{ $adminCount }}, {{ $superAdminCount }}],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.6)',
                        'rgba(59, 130, 246, 0.6)',
                        'rgba(234, 179, 8, 0.6)'
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(234, 179, 8, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</x-app-layout>
