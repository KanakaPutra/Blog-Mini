<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6">

        <!-- 📰 Header ala The Archipelago Times -->
        <div class="flex items-center justify-between mb-10 border-b border-gray-300 pb-4">
            
            <!-- Kiri: tanggal -->
            <div class="text-gray-500 text-sm leading-tight font-serif">
                {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, F d, Y') }}<br>
                <span class="italic">Today's Paper</span>
            </div>

            <!-- Tengah: logo -->
            <div class="flex-shrink-0 text-center">
                <img src="{{ asset('images/the-times.png') }}" 
                     alt="The Archipelago Times" 
                     class="w-[350px] h-[50px] object-contain mx-auto">
            </div>

            <!-- Kanan: info saham -->
            <div class="text-sm leading-tight text-right font-serif">
                @php
                    $validPrice = is_numeric($btcPrice ?? null);
                    $validChange = is_numeric($btcChange ?? null);
                @endphp

                @if($validPrice && $validChange)
                    @php
                        $isUp = floatval($btcChange) > 0;
                        $arrow = $isUp ? '↑' : '↓';
                        $color = $isUp ? 'text-green-600' : 'text-red-600';
                    @endphp

                    <span class="{{ $color }} font-medium">
                        BTC/USDT {{ number_format((float) $btcPrice, 2) }}
                        ({{ $isUp ? '+' : '' }}{{ number_format((float) $btcChange, 4) }}%) {{ $arrow }}
                    </span><br>
                @else
                    <span class="text-gray-500">Gagal memuat data saham</span><br>
                @endif

                <span class="text-gray-600">
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
                </span>
            </div>
        </div>

        <!-- 🧭 Judul Beranda -->
        <h1 class="text-2xl font-semibold text-gray-800 mb-8 text-left font-serif">
            Beranda Artikel
        </h1>

        <!-- 📚 Grid Artikel -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($articles as $article)
                <div class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-150 flex flex-col">

                    @if($article->thumbnail)
                        <img src="{{ asset('storage/'.$article->thumbnail) }}" 
                             alt="{{ $article->title }}" 
                             class="w-full h-48 object-cover rounded-t-lg">
                    @endif

                    <div class="p-4 flex flex-col flex-grow">
                        <h2 class="text-lg font-semibold text-gray-800 mb-1 font-serif">
                            {{ $article->title }}
                        </h2>

                        <!-- safe protection -->
                        <p class="text-sm text-gray-500 mb-2">
                            {{ $article->category->name ?? 'Tidak ada kategori' }} •
                            {{ $article->user->name ?? 'User tidak ditemukan' }}
                        </p>

                        <p class="text-xs text-gray-400 mb-3">
                            Dipublikasikan pada {{ $article->created_at->translatedFormat('d F Y') }}
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

                            <!-- Tampilkan jumlah LIKE saja -->
                            <div class="flex items-center gap-1 text-sm text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 stroke-current {{ auth()->check() && $article->isLikedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-gray-400 fill-none' }}"
                                    viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span class="font-semibold">{{ $article->totalLikes() }}</span>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($articles->isEmpty())
            <p class="text-gray-500 mt-10 text-center font-serif">Belum ada artikel.</p>
        @endif
    </div>
</x-app-layout>
