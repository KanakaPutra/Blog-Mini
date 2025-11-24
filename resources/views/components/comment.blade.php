@props(['comment', 'depth' => 0, 'parentAuthor' => null])

<div x-data="{ 
    showReplyForm: false, 
    showReplies: false,
    replyContent: '',
    isSubmitting: false,
    replyCount: {{ $comment->total_replies_count }},
    submitReply() {
        this.isSubmitting = true;
        const formData = new FormData(this.$refs.form);
        
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
                // Append new comment HTML
                this.$refs.newRepliesContainer.insertAdjacentHTML('beforeend', data.html);
                
                // Update state
                this.replyContent = '';
                this.showReplyForm = false;
                this.replyCount++; // Increment count
                this.showReplies = true; // Auto open replies
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to post reply. Please try again.');
        })
        .finally(() => {
            this.isSubmitting = false;
        });
    }
}" class="group mb-3">

    {{-- Main Comment Row --}}
    <div class="flex gap-3">
        {{-- Avatar --}}
        <div class="flex-shrink-0 cursor-pointer">
            <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                {{ substr($comment->user->name ?? 'U', 0, 1) }}
            </div>
        </div>

        <div class="flex-grow">
            {{-- Header: Nama & Tanggal --}}
            <div class="flex items-baseline gap-2 flex-wrap">
                <div class="flex items-center gap-1">
                    <span class="font-semibold text-sm text-gray-900 cursor-pointer hover:underline">
                        {{ $comment->user->name ?? 'User' }}
                    </span>
                    
                    {{-- Indicator User > Target for Depth > 1 --}}
                    @if($depth > 1 && $parentAuthor)
                        <span class="text-gray-400 text-xs mx-0.5">&gt;</span>
                        <span class="font-medium text-sm text-gray-700">
                            {{ $parentAuthor }}
                        </span>
                    @endif
                </div>
                
                <span class="text-xs text-gray-500">
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            {{-- Konten --}}
            <div class="text-sm text-gray-800 mt-0.5 leading-relaxed">
                {{ $comment->content }}
            </div>

            {{-- Actions: Reply --}}
            <div class="flex items-center gap-4 mt-1.5">
                @auth
                    <button 
                        @click="
                            showReplyForm = !showReplyForm; 
                            $nextTick(() => $refs.replyInput.focus());
                        "
                        class="text-xs font-semibold text-gray-500 hover:text-gray-900 py-1 px-2 rounded-full hover:bg-gray-100 transition-colors">
                        Reply
                    </button>
                @endauth
            </div>

            {{-- Form Reply (Modern Design + AJAX) --}}
            @auth
                <div x-show="showReplyForm" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="display: none;"
                     class="mt-3 mb-4">
                    
                    <form x-ref="form" @submit.prevent="submitReply" class="flex flex-col gap-2">
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <input type="hidden" name="article_id" value="{{ $comment->article_id }}">
                        <input type="hidden" name="depth" value="{{ $depth }}">
                        
                        <div class="flex gap-3 items-start">
                            {{-- User Avatar (Small) --}}
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex-shrink-0 flex items-center justify-center text-xs text-gray-600 font-bold border border-gray-200">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            
                            <div class="flex-grow">
                                {{-- Modern Input Box --}}
                                <div class="relative">
                                    <textarea 
                                        x-ref="replyInput"
                                        x-model="replyContent"
                                        name="content" 
                                        rows="2" 
                                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none placeholder-gray-400"
                                        placeholder="Add a reply..."></textarea>
                                </div>

                                {{-- Buttons --}}
                                <div class="flex justify-end gap-2 mt-2">
                                    <button type="button" @click="showReplyForm = false" class="text-xs font-semibold text-gray-600 hover:bg-gray-100 px-3 py-2 rounded-full transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                        :disabled="!replyContent.trim() || isSubmitting"
                                        :class="replyContent.trim() && !isSubmitting ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                                        class="px-4 py-2 rounded-full text-xs font-bold transition-all transform active:scale-95 flex items-center gap-2">
                                        <span x-show="!isSubmitting">Reply</span>
                                        <span x-show="isSubmitting" class="animate-spin h-3 w-3 border-2 border-white border-t-transparent rounded-full"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    {{-- Nested Replies (OUTSIDE Main Flex) --}}
    <div class="mt-2 {{ $depth === 0 ? 'pl-11' : '' }}">
        @if($depth === 0)
            {{-- Tombol View Replies (Hanya di Root) --}}
            {{-- Render button if depth is 0, but toggle visibility with Alpine based on count --}}
            <button 
                x-show="replyCount > 0"
                @click="showReplies = !showReplies" 
                class="group/btn flex items-center gap-2 text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-full transition-colors w-fit mb-2"
                style="display: none;"> {{-- Default hidden to prevent flash, Alpine will show if count > 0 --}}
                <div class="flex items-center justify-center w-4 h-4 transition-transform duration-200" :class="showReplies ? 'rotate-180' : ''">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <span class="text-sm font-semibold">
                    <span x-text="showReplies ? 'Hide replies' : replyCount + ' replies'"></span>
                </span>
            </button>

            <div x-show="showReplies"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 max-h-0"
                x-transition:enter-end="opacity-100 max-h-[2000px]"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 max-h-[2000px]"
                x-transition:leave-end="opacity-0 max-h-0"
                style="display: none;"
                class="overflow-hidden space-y-3">
                
                {{-- Existing Replies --}}
                @if($comment->replies)
                    @foreach($comment->replies as $reply)
                        @include('components.comment', [
                            'comment' => $reply, 
                            'depth' => $depth + 1,
                            'parentAuthor' => $comment->user->name
                        ])
                    @endforeach
                @endif
                
                {{-- Container for new AJAX replies --}}
                <div x-ref="newRepliesContainer" class="space-y-3"></div>
            </div>
        @else
            {{-- Depth > 0: Render langsung tanpa accordion & tanpa indent tambahan --}}
            <div class="space-y-3 mt-2">
                @if($comment->replies)
                    @foreach($comment->replies as $reply)
                        @include('components.comment', [
                            'comment' => $reply, 
                            'depth' => $depth + 1,
                            'parentAuthor' => $comment->user->name
                        ])
                    @endforeach
                @endif
                
                {{-- Container for new AJAX replies --}}
                <div x-ref="newRepliesContainer" class="space-y-3"></div>
            </div>
        @endif
    </div>
</div>