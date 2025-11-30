<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6">

        <!-- 📰 Header ala The Archipelago Times -->
        <div
            class="flex flex-col md:flex-row items-center justify-between mb-10 border-b-4 border-double border-gray-800 pb-6 gap-6 md:gap-0">

            <!-- Kiri: tanggal -->
            <div class="text-center md:text-left order-2 md:order-1 w-full md:w-1/3">
                <div
                    class="text-gray-600 text-sm font-serif hover:text-gray-900 transition-colors duration-300 cursor-default">
                    <p class="uppercase tracking-widest text-xs font-bold mb-1">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, F d, Y') }}</p>
                    <p class="italic text-lg">Today's Paper</p>
                </div>
            </div>

            <!-- Tengah: logo -->
            <div class="flex-shrink-0 text-center order-1 md:order-2 w-full md:w-1/3">
                <div class="group relative inline-block">
                    <img src="{{ asset('images/the-times.png') }}" alt="The Archipelago Times"
                        class="w-[280px] md:w-[400px] h-auto object-contain mx-auto transform transition-transform duration-500 group-hover:scale-105 filter drop-shadow-sm">
                    <div
                        class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gray-800 transition-all duration-300 group-hover:w-1/2 opacity-0 group-hover:opacity-100">
                    </div>
                </div>
            </div>

            <!-- Kanan: info saham (Carousel) -->
            <div class="text-center md:text-right order-3 w-full md:w-1/3 font-serif">
                <div class="inline-block" id="ticker-container">
                    <!-- Data will be populated by JS -->
                    <div id="ticker-content" class="transition-opacity duration-500 opacity-100">
                        <div class="flex flex-col items-center md:items-end">
                            <span class="text-xs text-gray-500 uppercase tracking-wide mb-1">Market Watch</span>
                            <span id="ticker-price" class="font-bold text-base flex items-center gap-1">
                                Loading...
                            </span>
                            <span id="ticker-change" class="text-xs px-2 py-0.5 rounded-full mt-1">
                                --
                            </span>
                        </div>
                    </div>

                    <div class="mt-1">
                        <span class="text-gray-500 text-xs font-mono">
                            UPDATED: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
                        </span>
                    </div>
                </div>

                <!-- Pass data to JS -->
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const tickers = @json($tickers ?? []);
                        let currentIndex = 0;

                        const priceEl = document.getElementById('ticker-price');
                        const changeEl = document.getElementById('ticker-change');
                        const containerEl = document.getElementById('ticker-content');

                        function updateTicker() {
                            if (tickers.length === 0) {
                                priceEl.innerText = "Data Unavailable";
                                return;
                            }

                            const ticker = tickers[currentIndex];
                            const isUp = parseFloat(ticker.change) > 0;
                            const arrow = isUp ? '▲' : '▼';
                            const colorClass = isUp ? 'text-green-700' : 'text-red-700';
                            const bgClass = isUp ? 'bg-green-50' : 'bg-red-50';

                            // Fade out
                            containerEl.classList.remove('opacity-100');
                            containerEl.classList.add('opacity-0');

                            setTimeout(() => {
                                // Update content
                                priceEl.className = `font-bold text-base flex items-center gap-1 ${colorClass}`;
                                priceEl.innerText = `${ticker.symbol} ${new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(ticker.price)}`;

                                changeEl.className = `text-xs px-2 py-0.5 rounded-full mt-1 ${colorClass} ${bgClass}`;
                                changeEl.innerText = `${isUp ? '+' : ''}${parseFloat(ticker.change).toFixed(4)}% ${arrow}`;

                                // Fade in
                                containerEl.classList.remove('opacity-0');
                                containerEl.classList.add('opacity-100');
                            }, 500); // Wait for fade out

                            currentIndex = (currentIndex + 1) % tickers.length;
                        }

                        // Initial run
                        updateTicker();

                        // Cycle every 4 seconds
                        setInterval(updateTicker, 4000);
                    });
                </script>
            </div>
        </div>

        <!-- 🧭 Judul Beranda -->
        <h1 class="text-2xl font-semibold text-gray-800 mb-8 text-left font-serif">
            Beranda Artikel
        </h1>

        <!-- 📚 Grid Artikel -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($articles as $article)
                <div
                    class="border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-150 flex flex-col">

                    @if($article->thumbnail)
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
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