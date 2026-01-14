<x-app-layout>
    <div class="max-w-[1440px] mx-auto py-12 px-6 lg:px-12" x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 50)">

        <!-- 🌐 Modern Symmetrical Header -->
        <div x-show="show" x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            class="mb-16 border-b border-gray-100 pb-12">

            <div class="grid grid-cols-1 lg:grid-cols-3 items-center gap-10 lg:gap-6">

                <!-- Left: Date & Info -->
                <div class="order-2 lg:order-1 flex flex-col items-center lg:items-start gap-1">
                    <span class="text-[10px] font-black text-blue-700 uppercase tracking-[0.3em]">Live Updates</span>
                    <div
                        class="flex flex-col items-center lg:items-start text-gray-600 font-bold uppercase tracking-widest text-[10px]">
                        <span
                            class="text-gray-900">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                        <div class="flex items-center gap-2 mt-1 opacity-80">
                            <span>Jakarta, ID</span>
                            <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                            <span id="header-clock"
                                class="text-gray-900 font-black">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }}
                                WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Center: Logo & Edition -->
                <div class="order-1 lg:order-2 flex flex-col items-center group">
                    <div class="mb-4">
                        <span
                            class="inline-flex px-3 py-1 bg-gray-900 text-white text-[9px] font-black uppercase tracking-[0.2em] rounded-full shadow-lg shadow-gray-200">
                            The Digital Edition
                        </span>
                    </div>
                    <a href="{{ route('dashboard') }}" class="relative block">
                        <img src="{{ asset('images/the-times.png') }}" alt="The Archipelago Times"
                            class="w-[280px] md:w-[380px] h-auto object-contain transition-all duration-500 group-hover:scale-[1.03] filter drop-shadow-sm">
                        <div
                            class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-blue-600 transition-all duration-500 group-hover:w-1/2 opacity-0 group-hover:opacity-100">
                        </div>
                    </a>
                </div>

                <!-- Right: Market Watch -->
                <div
                    class="order-3 lg:order-3 flex flex-col items-center lg:items-end gap-3 ">
                    <div id="ticker-container"
                        class="relative overflow-hidden bg-white px-5 py-3 rounded-2xl border border-gray-200 shadow-md shadow-blue-500/5 hover:shadow-lg transition-all duration-300 group/market">
                        <div id="ticker-content"
                            class="transition-all duration-700 opacity-100 flex flex-col items-center lg:items-end">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-1.5 h-1.5 bg-emerald-600 rounded-full animate-pulse"></span>
                                <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest">Market
                                    Watch</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span id="ticker-price"
                                    class="font-black text-sm tracking-tighter text-gray-900 flex items-center gap-2">Connecting...</span>
                                <span id="ticker-change" class="text-[10px] font-black px-2 py-0.5 rounded-lg">--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const tickers = @json($tickers ?? []);
                    let currentIndex = 0;
                    const priceEl = document.getElementById('ticker-price');
                    const changeEl = document.getElementById('ticker-change');
                    const containerEl = document.getElementById('ticker-content');
                    const clockEl = document.getElementById('header-clock');

                    function updateClock() {
                        const now = new Date();
                        clockEl.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
                    }
                    setInterval(updateClock, 30000);

                    function updateTicker() {
                        if (!tickers || tickers.length === 0) return;
                        const ticker = tickers[currentIndex];
                        const isUp = parseFloat(ticker.change) > 0;
                        const colorClass = isUp ? 'text-emerald-600' : 'text-rose-600';
                        const bgClass = isUp ? 'bg-emerald-50' : 'bg-rose-50';

                        containerEl.style.opacity = '0';
                        containerEl.style.transform = 'translateX(10px)';

                        setTimeout(() => {
                            priceEl.className = `font-black text-sm tracking-tighter flex items-center gap-2 ${colorClass}`;
                            priceEl.innerText = `${ticker.symbol} ${new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(ticker.price)}`;
                            changeEl.className = `text-[10px] font-black px-2 py-0.5 rounded-lg ${colorClass} ${bgClass}`;
                            changeEl.innerText = `${isUp ? '↑' : '↓'} ${Math.abs(parseFloat(ticker.change)).toFixed(4)}%`;

                            containerEl.style.opacity = '1';
                            containerEl.style.transform = 'translateX(0)';
                        }, 500);
                        currentIndex = (currentIndex + 1) % tickers.length;
                    }
                    if (tickers.length > 0) { updateTicker(); setInterval(updateTicker, 6000); }
                });
            </script>
        </div>

        @if($articles->isNotEmpty())
            <!-- 🗞️ Hero / Featured Section -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-24">
                @php $featured = $articles->first(); @endphp

                <!-- Main Featured Article -->
                <div class="lg:col-span-8 group cursor-pointer">
                    <div class="flex flex-col gap-5">
                        <div class="aspect-[16/9] w-full overflow-hidden rounded-md bg-gray-100 shadow-xl shadow-blue-900/5">
                            <img src="{{ $featured->thumbnail_url }}" alt="{{ $featured->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-3">
                                <span class="bg-blue-700 text-white text-[9px] font-black uppercase tracking-[0.2em] px-2.5 py-0.5 rounded shadow-lg shadow-blue-500/30">
                                    {{ $featured->category->name ?? 'Update' }}
                                </span>
                                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $featured->created_at->diffForHumans() }}</span>
                            </div>
                            <h2 class="text-3xl lg:text-5xl font-black text-gray-900 leading-[1.1] tracking-tighter group-hover:text-blue-700 transition-colors">
                                <a href="{{ route('articles.show', $featured->id) }}">{{ $featured->title }}</a>
                            </h2>
                            <p class="text-gray-700 text-base lg:text-lg leading-relaxed max-w-2xl font-medium">
                                {{ Str::limit(strip_tags($featured->content), 180, '...') }}
                            </p>
                            <div class="flex items-center justify-between pt-5 border-t border-gray-100 mt-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-extrabold text-xs ring-2 ring-white shadow-sm">
                                        {{ substr($featured->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-gray-900 leading-tight">{{ $featured->user->name }}</span>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Reporter</span>
                                    </div>
                                </div>
                                @include('partials.article-actions', ['article' => $featured])
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trending / Side Panel -->
                <div class="lg:col-span-4 border-l border-gray-100 lg:pl-10 flex flex-col gap-8">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-900 border-b-2 border-gray-900 pb-3">Most Popular</h3>
                    <div class="flex flex-col gap-10">
                        @foreach($articles->slice(1, 4) as $index => $side)
                            <div class="flex flex-col gap-3 group">
                                <div class="flex items-start gap-3">
                                    <span class="text-4xl font-black text-gray-100 group-hover:text-blue-600 transition-colors leading-[0.8]">0{{ $index + 1 }}</span>
                                    <div class="flex flex-col gap-1.5">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-700">{{ $side->category->name ?? 'Focus' }}</span>
                                        <h4 class="text-lg font-extrabold text-gray-900 leading-tight group-hover:text-blue-700 transition-colors tracking-tight">
                                            <a href="{{ route('articles.show', $side->id) }}">{{ $side->title }}</a>
                                        </h4>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-3 border-t border-gray-50 border-dashed">
                                    <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $side->created_at->translatedFormat('d M') }}</span>
                                    @include('partials.article-actions', ['article' => $side])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 📊 Bloomberg-Style Feed Grid -->
            <div class="mb-24">
                <div class="flex items-baseline justify-between mb-8 border-b-2 border-gray-900 pb-3">
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter italic">The Briefing</h3>
                    <div class="flex items-center border border-gray-100 rounded-lg p-0.5 bg-gray-50">
                        <button class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 bg-white rounded shadow-sm text-blue-700">All Posts</button>
                        <button class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 text-gray-400 hover:text-gray-900">Analysis</button>
                        <button class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 text-gray-400 hover:text-gray-900">Opinion</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-12 border-b border-gray-100 pb-12">
                    @foreach($articles->skip(5)->take(8) as $gridItem)
                        <div class="flex flex-col gap-5 md:px-4 md:first:pl-0 md:last:pr-0 md:border-r border-gray-100 md:last:border-0 group">
                            <div class="aspect-[3/2] w-full overflow-hidden rounded-md bg-gray-50 shadow-md shadow-gray-200/50">
                                <img src="{{ $gridItem->thumbnail_url }}" alt="{{ $gridItem->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-blue-700">{{ $gridItem->category->name ?? 'News' }}</span>
                                <h4 class="text-base lg:text-lg font-extrabold text-gray-900 leading-tight tracking-tight group-hover:text-blue-700 transition-colors line-clamp-3">
                                    <a href="{{ route('articles.show', $gridItem->id) }}">{{ $gridItem->title }}</a>
                                </h4>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-1">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $gridItem->created_at->translatedFormat('d M Y') }}</span>
                                    @include('partials.article-actions', ['article' => $gridItem])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 🏛️ Editorial "Section Buckets" -->
            @php
                $categorized = $articles->groupBy(fn($a) => $a->category->name ?? 'Uncategorized')->take(3);
            @endphp
            <div class="space-y-24">
                @foreach($categorized as $categoryName => $catArticles)
                    <section class="border-t-2 border-gray-900 pt-10">
                        <div class="flex items-center justify-between mb-12 px-2">
                            <h3 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tighter uppercase italic leading-none">{{ $categoryName }}</h3>
                            <a href="#" class="text-[9px] font-black text-gray-900 uppercase tracking-[0.3em] border-[1.5px] border-gray-900 px-4 py-1.5 hover:bg-gray-900 hover:text-white transition-all">View {{ $categoryName }}</a>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                            <!-- Left Big Story -->
                            <div class="lg:col-span-7 lg:border-r border-gray-100 lg:pr-10 group">
                                @php $bigCat = $catArticles->first(); @endphp
                                <div class="flex flex-col gap-6">
                                    <div class="aspect-[16/10] w-full overflow-hidden rounded-md shadow-xl shadow-gray-900/10">
                                        <img src="{{ $bigCat->thumbnail_url }}" alt="{{ $bigCat->title }}" 
                                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <h4 class="text-2xl lg:text-3xl font-extrabold text-gray-900 leading-tight tracking-tighter group-hover:text-blue-700 transition-colors">
                                            <a href="{{ route('articles.show', $bigCat->id) }}">{{ $bigCat->title }}</a>
                                        </h4>
                                        <p class="text-gray-700 text-sm lg:text-base leading-relaxed font-medium">
                                            {{ Str::limit(strip_tags($bigCat->content), 150, '...') }}
                                        </p>
                                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">{{ $bigCat->created_at->translatedFormat('d M Y') }}</span>
                                            @include('partials.article-actions', ['article' => $bigCat])
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side List -->
                            <div class="lg:col-span-5 flex flex-col gap-6 lg:mt-0 mt-10">
                                @foreach($catArticles->skip(1)->take(3) as $catItem)
                                    <div class="flex gap-4 md:gap-5 group pb-6 border-b border-gray-50 last:border-0 last:pb-0">
                                        <div class="flex-shrink-0 w-20 h-20 md:w-28 md:h-28 rounded-md overflow-hidden bg-gray-50 border border-gray-200">
                                            <img src="{{ $catItem->thumbnail_url }}" alt="{{ $catItem->title }}" 
                                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                        </div>
                                        <div class="flex flex-col justify-center gap-1.5 flex-1 min-w-0">
                                            <h4 class="text-base font-extrabold text-gray-900 leading-tight group-hover:text-blue-700 transition-colors font-sans tracking-tight line-clamp-2">
                                                <a href="{{ route('articles.show', $catItem->id) }}">{{ $catItem->title }}</a>
                                            </h4>
                                            <p class="text-[10px] text-gray-600 font-bold line-clamp-1 md:line-clamp-2 leading-relaxed">
                                                {{ Str::limit(strip_tags($catItem->content), 70, '...') }}
                                            </p>
                                            <div class="flex items-center gap-3 mt-0.5">
                                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $catItem->created_at->translatedFormat('d M') }}</span>
                                                @include('partials.article-actions', ['article' => $catItem])
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endforeach
            </div>
        @else
            <div class="py-32 text-center flex flex-col items-center gap-6">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15" />
                    </svg>
                </div>
                <p class="text-gray-400 text-2xl font-black uppercase tracking-tighter italic">The archives are empty.</p>
                <div class="w-12 h-1 bg-blue-600 rounded-full"></div>
            </div>
        @endif
    </div>

    <!-- ☁️ Ultra-Clean Footer -->
    <footer class="bg-gray-900 text-white mt-40">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 py-24">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-16">
                <div class="lg:col-span-2 flex flex-col gap-8">
                    <h2 class="text-5xl font-black tracking-tighter uppercase leading-none">THE TIMES.</h2>
                    <p class="text-gray-400 text-lg leading-relaxed max-w-sm font-medium opacity-80">Empowering the
                        world through bold, independent journalism. The core of the archipelago, delivered globally.</p>
                    <div class="flex flex-wrap gap-3 sm:gap-6">
                        @foreach(['Twitter', 'Instagram', 'LinkedIn', 'Youtube'] as $social)
                            <a href="#"
                                class="text-[10px] font-black uppercase tracking-[0.2em] text-white hover:text-blue-400 transition-colors border border-white/10 px-4 py-2 rounded-full cursor-pointer whitespace-nowrap">{{ $social }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <h3 class="text-xs font-black uppercase tracking-widest text-blue-500">Corporate</h3>
                    <div class="flex flex-col gap-3 text-sm font-bold text-gray-400">
                        <a href="#" class="hover:text-white transition-colors">Our History</a>
                        <a href="#" class="hover:text-white transition-colors">Ethics Policy</a>
                        <a href="#" class="hover:text-white transition-colors">Career</a>
                        <a href="#" class="hover:text-white transition-colors">Advertise</a>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <h3 class="text-xs font-black uppercase tracking-widest text-blue-500">Stay Updated</h3>
                    <p class="text-xs text-gray-500 leading-relaxed font-bold uppercase tracking-widest">Get our weekly
                        insights delivered directly to your inbox.</p>
                    <div class="flex border-b border-gray-700 pb-2">
                        <input type="text" placeholder="EMAIL ADDRESS"
                            class="bg-transparent border-none text-[10px] font-black uppercase tracking-widest focus:ring-0 w-full placeholder:text-gray-700">
                        <button class="text-blue-500 hover:text-blue-400 font-black text-xs">&rarr;</button>
                    </div>
                </div>
            </div>
            <div
                class="mt-24 pt-12 border-t border-white/5 flex flex-col lg:flex-row justify-between items-center gap-6">
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">&copy; {{ date('Y') }} THE
                    ARCHIPELAGO TIMES GROUP. ALL RIGHTS RESERVED.</span>
                <div class="flex gap-8 text-[10px] font-black text-gray-500 uppercase tracking-widest">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Cookie Settings</a>
                </div>
            </div>
        </div>
    </footer>
</x-app-layout> 