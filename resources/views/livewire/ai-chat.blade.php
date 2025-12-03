<div x-data="{ open: false, scrollBottom() { $nextTick(() => { $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight }) } }"
    x-init="$watch('$wire.messages', () => scrollBottom())"
    class="fixed bottom-5 right-5 z-50 flex flex-col items-end font-sans">

    <!-- Chat Window -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-95"
        class="bg-white dark:bg-zinc-800 w-80 sm:w-96 h-[500px] max-h-[80vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-zinc-200 dark:border-zinc-700 mb-4"
        style="display: none;">

        <!-- Header -->
        <div class="bg-indigo-600 p-4 flex justify-between items-center text-white shadow-md z-10">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div
                        class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 border-2 border-indigo-600 rounded-full">
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-sm">AI Assistant</h3>
                    <p class="text-xs text-indigo-200">Online & Ready</p>
                </div>
            </div>
            <button @click="open = false"
                class="hover:bg-indigo-700/50 p-1.5 rounded-lg transition text-indigo-100 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Messages -->
        <div x-ref="chatContainer"
            class="flex-1 overflow-y-auto p-4 space-y-4 bg-zinc-50 dark:bg-zinc-900 scroll-smooth">
            @foreach($messages as $msg)
                <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }} animate-fade-in-up">
                    <div
                        class="max-w-[85%] rounded-2xl px-4 py-2.5 text-sm shadow-sm {{ $msg['role'] === 'user' ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 border border-zinc-200 dark:border-zinc-700 rounded-bl-none' }}">
                        @if($msg['role'] === 'assistant')
                            <div
                                class="prose prose-sm max-w-none dark:prose-invert prose-p:my-1 prose-ul:my-1 prose-li:my-0.5 prose-strong:text-indigo-600 dark:prose-strong:text-indigo-400">
                                {!! Str::markdown($msg['content']) !!}
                            </div>
                        @else
                            {{ $msg['content'] }}
                        @endif
                    </div>
                </div>
            @endforeach

            @if($isLoading)
                <div class="flex justify-start animate-pulse">
                    <div
                        class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl rounded-bl-none px-4 py-3 shadow-sm">
                        <div class="flex space-x-1.5">
                            <div class="w-1.5 h-1.5 bg-zinc-400 rounded-full animate-bounce"></div>
                            <div class="w-1.5 h-1.5 bg-zinc-400 rounded-full animate-bounce" style="animation-delay: 0.1s">
                            </div>
                            <div class="w-1.5 h-1.5 bg-zinc-400 rounded-full animate-bounce" style="animation-delay: 0.2s">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Input -->
        <div class="p-3 bg-white dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700">
            @auth
                @if(Auth::user()->is_admin || $remainingMessages > 0)
                    <div class="mb-2 text-xs text-center text-zinc-500 dark:text-zinc-400">
                        @if(Auth::user()->is_admin)
                            <span class="font-bold text-indigo-600">Unlimited Access (Admin)</span>
                        @else
                            Sisa kuota hari ini: <span class="font-bold text-indigo-600">{{ $remainingMessages }}</span> pesan
                        @endif
                    </div>
                    <form wire:submit.prevent="sendMessage" class="flex gap-2 items-center">
                        <input wire:model="userMessage" type="text" placeholder="Tanya sesuatu..."
                            class="flex-1 bg-zinc-100 dark:bg-zinc-900 border-0 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white placeholder-zinc-400 transition-shadow">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-xl transition shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none flex-shrink-0"
                            wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </button>
                    </form>
                @else
                    <div class="text-center py-2">
                        <p class="text-sm text-red-500 font-medium mb-1">Kuota Harian Habis</p>
                        <p class="text-xs text-zinc-500">Silakan kembali lagi besok!</p>
                    </div>
                @endif
            @else
                <div class="text-center py-2">
                    <p class="text-sm text-zinc-600 dark:text-zinc-300 mb-2">Login untuk chat dengan AI</p>
                    <a href="{{ route('login') }}"
                        class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-4 py-2 rounded-lg transition">
                        Masuk Sekarang
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Toggle Button -->
    <button @click="open = !open"
        class="group bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-full shadow-xl transition-all hover:scale-110 focus:outline-none focus:ring-4 focus:ring-indigo-300 relative overflow-hidden"
        x-show="!open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100">

        <div
            class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 rounded-full">
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 relative z-10" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>

        <!-- Notification Dot (Optional) -->
        <span
            class="absolute top-0 right-0 block h-3 w-3 transform -translate-y-1/2 translate-x-1/2 rounded-full ring-2 ring-white bg-red-500"></span>
    </button>

</div>