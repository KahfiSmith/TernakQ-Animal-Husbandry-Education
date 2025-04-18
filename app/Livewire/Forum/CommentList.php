<?php

namespace App\Livewire\Forum;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Comment;
use App\Models\Topic;

class CommentList extends Component
{
    public $topic;
    public $newComment = '';
    public $replyingTo = null;
    public $parentComment = null;
    
    protected $rules = [
        'newComment' => 'required|min:3|max:500'
    ];
    
    #[On('commentAdded')]
    public function refreshComments()
    {
        $this->topic = Topic::with(['comments' => function($query) {
            $query->with(['user', 'replies.user']);
        }])->find($this->topic->id);
    }
    
    public function mount(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function startReply($commentId)
    {
        $this->replyingTo = $commentId;
        $this->parentComment = Comment::find($commentId);
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->parentComment = null;
    }
    
    public function addComment()
    {
        $this->validate();
        
        $comment = Comment::create([
            'topic_id' => $this->topic->id,
            'user_id' => auth()->id(),
            'content' => $this->newComment,
            'parent_id' => $this->replyingTo
        ]);
        
        $this->reset('newComment');
        $this->cancelReply();
        
        return redirect(request()->header('Referer'));
    }

    protected $listeners = [
        'commentAdded' => '$refresh',
        'refreshComponent' => '$refresh'
    ];
    
    public function render()
    {
        $comments = $this->topic->comments()
        ->with(['user', 'likes', 'replies.user', 'replies.likes']) 
        ->whereNull('parent_id')
        ->latest()
        ->get();
        
        return view('livewire.forum.comment-list', [
            'comments' => $comments
        ]);
    }
}