<div x-data="{ 
    open: false, 
    activeTab: 'home',
    isViewingConversation: false,
    currentArticle: null,
    articles: {
        'getting-started': {
            title: 'Getting started with blog',
            content: 'Selamat datang di The Archipelago Times! Untuk mulai membaca, kamu bisa menjelajahi berbagai kategori di menu utama. Jika ingin berkontribusi, pastikan kamu sudah mendaftar akun dan memverifikasi email kamu.'
        },
        'how-to-write': {
            title: 'How to write an article',
            content: 'Menulis artikel sangat mudah. Masuk ke Dashboard Penulis, klik \'Tulis Artikel Baru\', isi judul yang menarik, tambahkan konten berkualitas, dan pilih kategori yang sesuai. Jangan lupa tambahkan thumbnail yang relevan!'
        },
        'managing-profile': {
            title: 'Managing your profile',
            content: 'Kamu bisa mengubah foto profil, bio, dan pengaturan akun lainnya melalui menu Pengaturan Profil. Pastikan informasi kamu selalu mutakhir agar pembaca bisa mengenal kamu lebih baik.'
        },
        'security-best-practices': {
            title: 'Security best practices',
            content: 'Selalu gunakan kata sandi yang kuat dan unik. Aktifkan autentikasi dua faktor (2FA) jika tersedia. Jangan pernah memberikan kredensial login kamu kepada siapa pun, termasuk tim kami.'
        },
        'subscription-plans': {
            title: 'Understanding subscription plans',
            content: 'Kami menawarkan berbagai paket langganan mulai dari Gratis hingga Premium. Paket Premium memberikan akses tanpa batas ke konten eksklusif dan fitur-fitur khusus bagi penulis pro.'
        },
        'account-creation': {
            title: 'How to create an account',
            content: 'Klik tombol Daftar di pojok kanan atas, isi formulir pendaftaran dengan data yang valid, dan ikuti petunjuk verifikasi yang dikirimkan ke email kamu.'
        }
    },
    scrollBottom() { 
        $nextTick(() => { 
            const container = $refs.chatContainer;
            if (container) {
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
            }
        });
    } 
}" x-init="
        $watch('activeTab', value => { 
            if(value === 'messages' && isViewingConversation) scrollBottom(); 
            currentArticle = null;
        });
        $watch('isViewingConversation', value => { if(value) scrollBottom(); });
        $watch('$wire.messages', () => scrollBottom());
        Livewire.on('messageSent', () => {
            activeTab = 'messages';
            isViewingConversation = true;
            currentArticle = null;
            scrollBottom();
        });
    "
    class="fixed bottom-0 left-0 right-0 p-4 sm:bottom-6 sm:right-6 sm:left-auto sm:p-0 z-50 flex flex-col items-end font-sans pointer-events-none">

    <div class="pointer-events-auto flex flex-col items-end">

        <!-- Chat Widget Modal -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 scale-95"
            class="bg-white dark:bg-[#001E2B] w-full sm:w-[400px] h-[600px] max-h-[85vh] rounded-[24px] shadow-[0_20px_50px_rgba(0,0,0,0.3)] flex flex-col overflow-hidden border border-zinc-200 dark:border-[#1C2D38] mb-4 relative"
            style="display: none;" x-cloak>

            <!-- HEADERS -->
            <div class="shrink-0">
                <!-- Article Detail Header -->
                <template x-if="currentArticle">
                    <div
                        class="bg-white dark:bg-[#001E2B] p-5 border-b border-zinc-100 dark:border-[#1C2D38] flex justify-between items-center relative">
                        <button @click="currentArticle = null"
                            class="p-1 hover:bg-zinc-100 dark:hover:bg-[#1C2D38] rounded-lg transition text-zinc-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <h2 class="text-sm font-bold dark:text-white text-center flex-1 tracking-tight truncate px-2"
                            x-text="articles[currentArticle].title"></h2>
                        <button @click="open = false"
                            class="p-1.5 hover:bg-zinc-100 dark:hover:bg-[#1C2D38] rounded-lg transition text-zinc-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                <template x-if="!currentArticle && activeTab === 'home'">
                    <div class="bg-gradient-to-br from-[#001E2B] to-[#00684A] p-6 text-white relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-[#00ED64]/10 rounded-full -mr-16 -mt-16 blur-3xl">
                        </div>
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <h2 class="text-2xl font-bold tracking-tight">Hello there</h2>
                                <p class="text-zinc-300 text-sm mt-1">How can we help you today?</p>
                            </div>
                            <button @click="open = false" class="p-1 hover:bg-white/10 rounded-lg transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>

                <template x-if="!currentArticle && activeTab === 'messages' && !isViewingConversation">
                    <div
                        class="bg-white dark:bg-[#001E2B] p-5 border-b border-zinc-100 dark:border-[#1C2D38] flex justify-between items-center relative">
                        <div class="flex-1"></div>
                        <h2 class="text-lg font-bold dark:text-white text-center flex-1">Messages</h2>
                        <div class="flex-1 flex justify-end">
                            <button @click="open = false"
                                class="p-1.5 hover:bg-zinc-100 dark:hover:bg-[#1C2D38] rounded-lg transition text-zinc-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>

                <template x-if="!currentArticle && activeTab === 'messages' && isViewingConversation">
                    <div
                        class="bg-[#001E2B] p-5 flex justify-between items-center text-white border-b border-[#1C2D38]">
                        <div class="flex items-center gap-3">
                            <button @click="isViewingConversation = false"
                                class="p-1 hover:bg-white/10 rounded-lg transition mr-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div
                                class="w-10 h-10 bg-[#00ED64] rounded-full flex items-center justify-center font-bold text-[#001E2B]">
                                A</div>
                            <div>
                                <h3 class="font-bold text-sm tracking-tight text-white">Archipelago AI</h3>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-[#00ED64] rounded-full animate-pulse"></span>
                                    <p class="text-[10px] uppercase font-bold text-zinc-400">Online</p>
                                </div>
                            </div>
                        </div>
                        <button @click="open = false" class="p-1 hover:bg-white/10 rounded-lg transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>

                <template x-if="!currentArticle && activeTab === 'help'">
                    <div
                        class="bg-white dark:bg-[#001E2B] p-5 border-b border-zinc-100 dark:border-[#1C2D38] flex justify-between items-center relative">
                        <div class="flex-1"></div>
                        <h2 class="text-lg font-bold dark:text-white text-center flex-1 tracking-tight">Help center</h2>
                        <div class="flex-1 flex justify-end">
                            <button @click="open = false"
                                class="p-1.5 hover:bg-zinc-100 dark:hover:bg-[#1C2D38] rounded-lg transition text-zinc-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto bg-white dark:bg-[#001E2B] custom-scrollbar relative overflow-x-hidden">

                <!-- Article Detail View -->
                <div x-show="currentArticle" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="p-6 bg-white dark:bg-[#001E2B] min-h-full">
                    <template x-if="currentArticle">
                        <div class="space-y-4">
                            <h1 class="text-xl font-bold dark:text-white" x-text="articles[currentArticle].title"></h1>
                            <div class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400"
                                x-text="articles[currentArticle].content"></div>
                            <div class="pt-8 border-t border-zinc-100 dark:border-[#1C2D38]">
                                <p
                                    class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">
                                    Was this article helpful?</p>
                                <div class="flex gap-2 mt-3">
                                    <button
                                        class="px-4 py-2 bg-white dark:bg-[#1C2D38] border border-zinc-200 dark:border-[#2A3E4A] rounded-xl text-xs font-bold text-zinc-700 dark:text-zinc-300 hover:border-[#00ED64] hover:text-[#00ED64] hover:bg-[#00ED64]/5 transition-all">Yes</button>
                                    <button
                                        class="px-4 py-2 bg-white dark:bg-[#1C2D38] border border-zinc-200 dark:border-[#2A3E4A] rounded-xl text-xs font-bold text-zinc-700 dark:text-zinc-300 hover:border-[#00ED64] hover:text-[#00ED64] hover:bg-[#00ED64]/5 transition-all">No</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- HOME TAB -->
                <div x-show="!currentArticle && activeTab === 'home'"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" class="p-4 space-y-4 bg-zinc-50 dark:bg-[#001E2B] min-h-full">
                    <!-- Recent Message Card -->
                    <div @click="activeTab = 'messages'; isViewingConversation = true; scrollBottom();"
                        class="bg-white dark:bg-[#1C2D38] p-4 rounded-2xl shadow-sm border border-zinc-100 dark:border-[#2A3E4A] hover:shadow-md transition cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 bg-[#00ED64] rounded-full flex items-center justify-center font-bold text-[#001E2B]">
                                    A</div>
                                <div
                                    class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-[#1C2D38] rounded-full">
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Recent
                                        Message</span>
                                    <span class="text-[10px] text-zinc-400">10m ago</span>
                                </div>
                                <p class="text-sm font-semibold dark:text-white mt-0.5 line-clamp-1">Archipelago AI:
                                    Halo!
                                    Saya siap membantu apa pun yang...</p>
                            </div>
                            <svg class="w-4 h-4 text-zinc-300 group-hover:text-[#00ED64] transition" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="flex items-center gap-2 px-1">
                        <div class="w-5 h-5 bg-green-500/10 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">All Systems
                            Operational</span>
                    </div>

                    <!-- Search Input -->
                    <div class="relative group">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" placeholder="Search for help"
                            class="w-full bg-white dark:bg-[#1C2D38] border-zinc-200 dark:border-[#2A3E4A] rounded-2xl py-3 pl-10 pr-4 text-sm text-zinc-800 dark:text-white font-bold focus:ring-2 focus:ring-[#00ED64] focus:border-transparent transition-all shadow-sm">
                    </div>

                    <!-- Help Articles -->
                    <div
                        class="bg-white dark:bg-[#1C2D38] rounded-2xl shadow-sm border border-zinc-100 dark:border-[#2A3E4A] overflow-hidden">
                        <div class="p-4 border-b border-zinc-100 dark:border-[#2A3E4A]">
                            <h3 class="font-bold text-sm dark:text-white uppercase tracking-widest text-[10px]">
                                Suggested
                                Articles</h3>
                        </div>
                        <div class="divide-y divide-zinc-50 dark:divide-[#2A3E4A]">
                            <template x-for="(art, slug) in { 
                            'getting-started': articles['getting-started'], 
                            'how-to-write': articles['how-to-write'], 
                            'managing-profile': articles['managing-profile'] 
                        }" :key="slug">
                                <button @click="currentArticle = slug"
                                    class="w-full flex justify-between items-center p-4 hover:bg-zinc-50 dark:hover:bg-[#2A3E4A] transition group text-left">
                                    <span class="text-sm text-zinc-700 dark:text-zinc-300" x-text="art.title"></span>
                                    <svg class="w-4 h-4 text-zinc-300 group-hover:text-[#00ED64] transition" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Ask Question CTA -->
                    <div @click="activeTab = 'messages'; isViewingConversation = true; scrollBottom();"
                        class="bg-white dark:bg-[#1C2D38] p-4 rounded-2xl shadow-sm border border-zinc-100 dark:border-[#2A3E4A] hover:shadow-md transition cursor-pointer group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 bg-[#00ED64]/10 rounded-xl flex items-center justify-center text-[#00ED64]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm dark:text-white">Ask a question</h4>
                                <p class="text-xs text-zinc-500">AI Agent and team can help</p>
                            </div>
                            <div class="ml-auto">
                                <svg class="w-5 h-5 text-[#00ED64] group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MESSAGES TAB -->
                <div x-show="!currentArticle && activeTab === 'messages'"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" class="min-h-full">
                    <!-- THREADS LIST -->
                    <div x-show="!isViewingConversation" class="min-h-full pb-24">
                        <div @click="isViewingConversation = true; scrollBottom();"
                            class="flex items-center gap-4 p-5 hover:bg-zinc-50 dark:hover:bg-[#1C2D38]/50 transition cursor-pointer group">
                            <div class="relative flex-shrink-0">
                                <div
                                    class="w-14 h-14 bg-[#00ED64] rounded-full flex items-center justify-center font-bold text-[#001E2B] text-lg">
                                    A</div>
                                <div
                                    class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-[#001E2B] rounded-full">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4 class="text-sm font-bold dark:text-white truncate">Archipelago AI</h4>
                                    <span class="text-xs text-zinc-400">3d</span>
                                </div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-1 mb-2">Halo! Saya siap
                                    membantu apa pun yang kamu butuhkan. Silakan tanya apa saja...</p>
                                <button
                                    @click.stop="activeTab = 'messages'; isViewingConversation = true; scrollBottom();"
                                    class="text-[10px] bg-[#00ED64]/10 hover:bg-[#00ED64]/20 text-[#00684A] dark:text-[#00ED64] font-bold py-1.5 px-3 rounded-lg transition border border-[#00ED64]/20 flex items-center gap-1.5 w-fit">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Use AI for faster response
                                </button>
                            </div>
                            <svg class="w-5 h-5 text-zinc-300 group-hover:text-[#00ED64] transition flex-shrink-0"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <div class="absolute bottom-6 left-0 right-0 flex justify-center pointer-events-none">
                            <button @click="isViewingConversation = true; scrollBottom();"
                                class="pointer-events-auto bg-[#00ED64] hover:bg-[#00D056] text-[#001E2B] font-bold py-3 px-6 rounded-full shadow-lg flex items-center gap-2 transition hover:scale-105 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Ask a question
                            </button>
                        </div>
                    </div>

                    <!-- ACTUAL CONVERSATION VIEW -->
                    <div x-show="isViewingConversation" x-ref="chatContainer"
                        class="flex flex-col min-h-full p-5 space-y-6 custom-scrollbar overflow-y-auto pb-48 bg-zinc-50 dark:bg-[#1C2D38]/20 transition-all">
                        @foreach($messages as $msg)
                            <div
                                class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }} group bounce-in">
                                <div
                                    class="flex flex-col {{ $msg['role'] === 'user' ? 'items-end' : 'items-start' }} max-w-[85%]">
                                    <div
                                        class="px-4 py-3 text-sm leading-relaxed {{ $msg['role'] === 'user' ? 'bg-[#00684A] text-white rounded-2xl rounded-tr-none shadow-md' : 'bg-white dark:bg-[#1C2D38] text-zinc-800 dark:text-zinc-100 border border-zinc-200 dark:border-[#2A3E4A] rounded-2xl rounded-tl-none shadow-sm' }}">
                                        @if($msg['role'] === 'assistant')
                                            <div
                                                class="prose prose-sm max-w-none dark:prose-invert prose-p:my-1 prose-ul:my-2 prose-li:my-1 prose-strong:text-[#00ED64] prose-a:text-[#00ED64]">
                                                {!! Str::markdown($msg['content']) !!}
                                            </div>
                                        @else
                                            {{ $msg['content'] }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div wire:loading wire:target="sendMessage, selectSuggestion" class="flex justify-start">
                            <div
                                class="bg-white dark:bg-[#1C2D38] border border-zinc-200 dark:border-[#2A3E4A] rounded-2xl rounded-tl-none px-5 py-3 shadow-sm flex items-center gap-2">
                                <div class="flex space-x-1">
                                    <div class="w-1.5 h-1.5 bg-[#00ED64] rounded-full animate-bounce"></div>
                                    <div
                                        class="w-1.5 h-1.5 bg-[#00ED64] rounded-full animate-bounce [animation-delay:-0.2s]">
                                    </div>
                                    <div
                                        class="w-1.5 h-1.5 bg-[#00ED64] rounded-full animate-bounce [animation-delay:-0.4s]">
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#00ED64]">AI is
                                    thinking...</span>
                            </div>
                        </div>

                        @if(count($messages) <= 1)
                            <div wire:loading.remove wire:target="sendMessage" class="pt-4 space-y-3">
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider px-1">Suggested for you
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($suggestions as $suggestion)
                                        <button wire:click="selectSuggestion('{{ $suggestion }}')"
                                            class="text-xs bg-white dark:bg-[#1C2D38] hover:bg-zinc-100 dark:hover:bg-[#2A3E4A] text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-[#2A3E4A] px-3 py-2 rounded-full transition-all">
                                            {{ $suggestion }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- HELP center TAB -->
                <div x-show="!currentArticle && activeTab === 'help'"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="p-5 space-y-6 bg-zinc-50 dark:bg-[#001E2B] min-h-full pb-24">
                    <div class="relative group">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" placeholder="Search for help"
                            class="w-full bg-white dark:bg-[#1C2D38] border-zinc-200 dark:border-[#2A3E4A] rounded-2xl py-3 pl-10 pr-4 text-sm text-zinc-800 dark:text-white font-bold focus:ring-2 focus:ring-[#00ED64] focus:border-transparent transition-all shadow-sm">
                    </div>

                    <div class="space-y-3">
                        <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-widest px-1">Suggested for you
                        </h3>
                        <div
                            class="bg-white dark:bg-[#1C2D38] rounded-2xl shadow-sm border border-zinc-100 dark:border-[#2A3E4A] overflow-hidden">
                            <div class="divide-y divide-zinc-50 dark:divide-[#2A3E4A]">
                                <template x-for="(art, slug) in articles" :key="slug">
                                    <button @click="currentArticle = slug"
                                        class="w-full flex justify-between items-center p-4 hover:bg-zinc-50 dark:hover:bg-[#2A3E4A] transition group text-left border-b border-zinc-50 dark:border-[#1C2D38]/50 last:border-0">
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300 font-medium"
                                            x-text="art.title"></span>
                                        <svg class="w-4 h-4 text-zinc-300 group-hover:text-[#00ED64] transition"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-widest px-1">Browse by Category
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            @php $helpcats = [['name' => 'Getting Started', 'icon' => '🚀', 'count' => 12], ['name' => 'Account', 'icon' => '👤', 'count' => 8], ['name' => 'Billing', 'icon' => '💳', 'count' => 5], ['name' => 'Features', 'icon' => '✨', 'count' => 15]]; @endphp
                            @foreach($helpcats as $cat)
                                <div
                                    class="bg-white dark:bg-[#1C2D38] p-4 rounded-2xl border border-zinc-100 dark:border-[#2A3E4A] hover:border-[#00ED64] hover:shadow-md transition cursor-pointer group">
                                    <div class="text-2xl mb-2">{{ $cat['icon'] }}</div>
                                    <h4
                                        class="text-sm font-bold dark:text-white group-hover:text-[#00ED64] transition text-zinc-800">
                                        {{ $cat['name'] }}
                                    </h4>
                                    <p class="text-[10px] text-zinc-400 font-medium mt-1">{{ $cat['count'] }} articles</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom Input Area -->
            <div x-show="!currentArticle && activeTab === 'messages' && isViewingConversation"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="p-4 bg-white dark:bg-[#001E2B] border-t border-zinc-100 dark:border-[#1C2D38] absolute bottom-[64px] left-0 right-0 z-20">
                @auth
                    @if($isAdmin || $remainingMessages > 0)
                        <form @submit.prevent="$wire.sendMessage($wire.userMessage); $wire.userMessage = '';"
                            class="relative group">
                            <textarea wire:model="userMessage" rows="1"
                                @keydown.enter.prevent="$wire.sendMessage($wire.userMessage); $wire.userMessage = '';"
                                placeholder="Send a message..."
                                class="w-full bg-zinc-100 dark:bg-[#1C2D38] border-0 rounded-xl pl-4 pr-12 py-3 text-sm focus:ring-2 focus:ring-[#00ED64] dark:text-white transition-all resize-none overflow-hidden"></textarea>
                            <button type="submit"
                                class="absolute right-2 top-2 text-[#00684A] dark:text-[#00ED64] p-2 hover:bg-[#00ED64]/10 rounded-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <p class="text-[10px] text-center text-red-500 font-bold">Daily Limit Reached</p>
                    @endif
                @else
                    <div class="text-center p-2"><a href="{{ route('login') }}"
                            class="text-xs font-bold text-[#00ED64]">Login
                            to chat</a></div>
                @endauth
            </div>

            <!-- Sticky Bottom Navigation -->
            <div
                class="bg-white dark:bg-[#001E2B] border-t border-zinc-100 dark:border-[#1C2D38] flex justify-around p-2 shrink-0 relative z-30">
                <button @click="activeTab = 'home'; isViewingConversation = false; currentArticle = null;"
                    class="flex flex-col items-center gap-1 p-2 min-w-[60px] transition-colors"
                    :class="activeTab === 'home' ? 'text-[#00ED64]' : 'text-zinc-300 hover:text-zinc-600'">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span class="text-[10px] font-bold">Home</span>
                </button>
                <button @click="activeTab = 'messages'; isViewingConversation = false; currentArticle = null;"
                    class="flex flex-col items-center gap-1 p-2 min-w-[60px] transition-colors"
                    :class="activeTab === 'messages' ? 'text-[#00ED64]' : 'text-zinc-300 hover:text-zinc-600'">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                        <path
                            d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                    </svg>
                    <span class="text-[10px] font-bold">Messages</span>
                </button>
                <button @click="activeTab = 'help'; isViewingConversation = false; currentArticle = null;"
                    class="flex flex-col items-center gap-1 p-2 min-w-[60px] transition-colors"
                    :class="activeTab === 'help' ? 'text-[#00ED64]' : 'text-zinc-300 hover:text-zinc-600'">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-[10px] font-bold">Help</span>
                </button>
            </div>
        </div>
        <!-- Toggle Button -->
        <button @click="open = !open"
            class="group flex items-center justify-center bg-[#00ED64] hover:bg-[#00D056] text-[#001E2B] w-14 h-14 rounded-full shadow-[0_10px_25px_rgba(0,237,100,0.4)] transition-all duration-300 hover:scale-110 active:scale-95 focus:outline-none focus:ring-4 focus:ring-[#00ED64]/30"
            x-show="!open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100">
            <svg class="w-6 h-6 animate-float" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
        </button>
    </div>
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
            }
        }

        .animate-float {
            animation: float 2s ease-in-out infinite;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 104, 74, 0.2);
            border-radius: 10px;
        }

        .bounce-in {
            animation: bounce-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes bounce-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</div>