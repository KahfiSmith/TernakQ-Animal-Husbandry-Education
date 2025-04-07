<div class="comment-section">
    @auth
        <form wire:submit.prevent="addComment" class="mb-8">
            <div class="mb-3">
                <textarea wire:model="newComment"
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                                    focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                                    rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700
                                    text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5 mb-6 @error('newComment') is-invalid @enderror"
                    rows="4" placeholder="tulis komentar..."></textarea>
                @error('newComment')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex items-center justify-start w-full">
                <x-primary-button type="submit"
                     class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 text-center bg-orange-500 py-2.5 px-4 rounded">
                    Posting Komentar
                </x-primary-button>
            </div>
        </form>
    @else
        <div class="alert alert-info">
            <a href="{{ route('login') }}">Log in</a> to post a comment
        </div>
    @endauth

    <div class="comments-list">
        @forelse($comments as $comment)
            <div class="comment mb-3">
                <div class="comment-header d-flex justify-content-between align-items-center">
                    <div class="comment-author flex">
                        <span class="font-semibold">{{ $comment->user->name }}</span>
                        <small class="text-muted ml-2">
                            {{ $comment->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
                <div class="comment-body">
                    {{ $comment->content }}
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada komentar</p>
        @endforelse
    </div>
</div>
