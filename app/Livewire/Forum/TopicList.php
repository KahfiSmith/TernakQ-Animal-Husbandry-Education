<?php

namespace App\Livewire\Forum;

use App\Models\Topic;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class TopicList extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    #[Url]
    public $search = '';
    
    // Listen for the topic created event
    protected $listeners = ['topicCreated' => '$refresh'];
    
    public function mount()
    {
        // Ambil nilai search dari query parameter jika ada
        $this->search = request()->get('search', '');
        
        // Listen for broadcast events from Echo
        $this->dispatch('listen-for-new-topic');
    }
    
    public function setSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }
    
    public function render()
    {
        $topics = Topic::with('user')
            ->withCount('comments', 'likes')
            ->when($this->search, function($query) {
                return $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
            
        return view('livewire.forum.topic-list', [
            'topics' => $topics
        ]);
    }
}