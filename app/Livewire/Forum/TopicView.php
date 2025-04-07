<?php

namespace App\Livewire\Forum;

use App\Models\Topic;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class TopicView extends Component
{
    public $topic;
    
    public function mount($topic)
    {
        try {
            // Ensure the topic is loaded and exists
            $this->topic = is_numeric($topic) 
                ? Topic::findOrFail($topic) 
                : $topic;

            // Log for debugging
            Log::info('Topic loaded', [
                'topic_id' => $this->topic->id,
                'topic_title' => $this->topic->title
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Topic loading error', [
                'error' => $e->getMessage(),
                'topic' => $topic
            ]);

            // Redirect or show error
            session()->flash('error', 'Topic not found');
            return redirect()->route('forum.index');
        }
    }
    
    public function render()
    {
        // Ensure topic is loaded with relationships
        $topic = Topic::with(['user', 'comments'])
            ->findOrFail($this->topic->id);

        return view('livewire.forum.topic-view'); // Tanpa layout dulu
    }
}
