@props(['comment', 'depth' => 0, 'parentAuthor' => null])

<div x-data="{ 
    showReplyForm: false, 
    showReplies: false,
    isRepliesAnimating: false,
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
                this.$refs.newRepliesContainer.insertAdjacentHTML('beforeend', data.html);
                this.replyContent = '';
                this.showReplyForm = false;
                this.replyCount++;
                this.showReplies = true;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to post reply. Please try again.');
        })
        .finally(() => {
            this.isSubmitting = false;
        });
    },
    isEditing: false,
    editContent: {{ json_encode($comment->content) }},
    originalContent: '',
    isUpdating: false,
    isDeleting: false,
    isDeleted: false,
    updateComment() {
        this.isUpdating = true;
        fetch('/comments/{{ $comment->id }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ content: this.editContent })
        })
        .then(async response => {
            if (!response.ok) {
                const text = await response.text();
                try {
                    const json = JSON.parse(text);
                    throw new Error(json.message || 'Server error');
                } catch (e) {
                    throw new Error(text || 'Server error');
                }
            }
            return response.json();
        })
        .then(data => {
            this.isEditing = false;
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Failed to update comment.');
        })
        .finally(() => {
            this.isUpdating = false;
        });
    },
    deleteComment() {
        if (!confirm('Are you sure you want to delete this comment?')) return;
        
        this.isDeleting = true;
        fetch('/comments/{{ $comment->id }}', {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(async response => {
            if (!response.ok) {
                const text = await response.text();
                try {
                    const json = JSON.parse(text);
                    throw new Error(json.message || 'Server error');
                } catch (e) {
                    throw new Error(text || 'Server error');
                }
            }
            return response.json();
        })
        .then(data => {
            this.isDeleted = true;
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Failed to delete comment.');
        })
        .finally(() => {
            this.isDeleting = false;
        });
    }
}" x-show="!isDeleted" class="group mb-3">

    {{-- Main Comment Row --}}
    <div class="flex gap-3">
        {{-- Avatar --}}
        <div class="flex-shrink-0 cursor-pointer">
            <div
                class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                {{ substr($comment->user->name ?? 'U', 0, 1) }}
            </div>
        </div>

        <div class="flex-grow relative">
            {{-- Header: Nama & Tanggal --}}
            <div class="flex items-baseline gap-2 flex-wrap pr-8">
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

            {{-- Action Menu (Three Dots) --}}
            @if(auth()->id() === $comment->user_id)
                <div class="absolute top-0 right-0" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-700 p-1.5 rounded-full hover:bg-gray-100 transition-all focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         style="display: none;"
                         class="absolute right-0 mt-1 w-36 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-20 ring-1 ring-black ring-opacity-5 overflow-hidden">
                        
                        <button @click="open = false; originalContent = editContent; isEditing = true; $nextTick(() => $refs.editInput.focus())" class="w-full text-left px-4 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit
                        </button>
                        
                        <button @click="open = false; deleteComment()" class="w-full text-left px-4 py-2.5 text-xs font-medium text-red-600 hover:bg-red-50 flex items-center gap-2 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            @endif

            {{-- Konten --}}
            <div x-show="!isEditing" class="text-sm text-gray-800 mt-0.5 leading-relaxed">
                <span x-text="editContent"></span>
            </div>

            {{-- Edit Form --}}
            <div x-show="isEditing" style="display: none;" class="mt-2">
                <textarea x-ref="editInput" x-model="editContent" rows="3"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none placeholder-gray-400"></textarea>
                <div class="flex justify-end gap-2 mt-2">
                    <button @click="isEditing = false; editContent = originalContent"
                        class="text-xs font-semibold text-gray-600 hover:bg-gray-100 px-3 py-1.5 rounded-full transition-colors">
                        Cancel
                    </button>
                    <button @click="updateComment()" :disabled="!editContent.trim() || isUpdating"
                        class="px-3 py-1.5 rounded-full text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isUpdating">Save</span>
                        <span x-show="isUpdating"
                            class="animate-spin h-3 w-3 border-2 border-white border-t-transparent rounded-full inline-block"></span>
                    </button>
                </div>
            </div>

            {{-- Actions: Reply --}}
            <div class="flex items-center gap-4 mt-1.5">
                @auth
                    <button @click="
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
                <div x-show="showReplyForm" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;" class="mt-3 mb-4">

                    <form x-ref="form" @submit.prevent="submitReply" class="flex flex-col gap-2">
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <input type="hidden" name="article_id" value="{{ $comment->article_id }}">
                        <input type="hidden" name="depth" value="{{ $depth }}">

                        <div class="flex gap-3 items-start">
                            {{-- User Avatar (Small) --}}
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex-shrink-0 flex items-center justify-center text-xs text-gray-600 font-bold border border-gray-200">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>

                            <div class="flex-grow">
                                {{-- Modern Input Box --}}
                                <div class="relative">
                                    <textarea x-ref="replyInput" x-model="replyContent" name="content" rows="2"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none placeholder-gray-400"
                                        placeholder="Add a reply..."></textarea>
                                </div>

                                {{-- Buttons --}}
                                <div class="flex justify-end gap-2 mt-2">
                                    <button type="button" @click="showReplyForm = false"
                                        class="text-xs font-semibold text-gray-600 hover:bg-gray-100 px-3 py-2 rounded-full transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" :disabled="!replyContent.trim() || isSubmitting"
                                        :class="replyContent.trim() && !isSubmitting ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                                        class="px-4 py-2 rounded-full text-xs font-bold transition-all transform active:scale-95 flex items-center gap-2">
                                        <span x-show="!isSubmitting">Reply</span>
                                        <span x-show="isSubmitting"
                                            class="animate-spin h-3 w-3 border-2 border-white border-t-transparent rounded-full"></span>
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
            <button x-show="replyCount > 0" @click="showReplies = !showReplies"
                class="group/btn flex items-center gap-2 text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-full transition-colors w-fit mb-2"
                style="display: none;"> {{-- Default hidden to prevent flash, Alpine will show if count > 0 --}}
                <div class="flex items-center justify-center w-4 h-4 transition-transform duration-200"
                    :class="showReplies ? 'rotate-180' : ''">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="w-3 h-3">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <span class="text-sm font-semibold">
                    <span x-text="showReplies ? 'Hide replies' : replyCount + ' replies'"></span>
                </span>
            </button>

            <div x-show="showReplies" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-[2000px]"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 max-h-[2000px]"
                x-transition:leave-end="opacity-0 max-h-0" 
                @transitionstart="if ($event.target === $el) isRepliesAnimating = true"
                @transitionend="if ($event.target === $el) isRepliesAnimating = false"
                :class="{ 'overflow-hidden': isRepliesAnimating }"
                style="display: none;" class="space-y-3">

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