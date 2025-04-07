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
    
    protected $rules = [
        'newComment' => 'required|min:3|max:500'
    ];
    
    #[On('commentAdded')]
    public function refreshComments()
    {
        // You can add additional logic here if needed
    }
    
    public function mount(Topic $topic)
    {
        $this->topic = $topic;
    }
    
    public function addComment()
    {
        $this->validate();
        
        $comment = Comment::create([
            'topic_id' => $this->topic->id,
            'user_id' => auth()->id(),
            'content' => $this->newComment
        ]);
        
        // Optional: Broadcast or additional event handling
        // event(new \App\Events\NewCommentAdded($comment));
        
        $this->reset('newComment');
        
        // Dispatch event to potentially refresh the comments
        $this->dispatch('commentAdded');
    }
    
    public function render()
    {
        $comments = $this->topic->comments()
            ->with('user')
            ->latest()
            ->get();
        
        return view('livewire.forum.comment-list', [
            'comments' => $comments
        ]);
    }
}