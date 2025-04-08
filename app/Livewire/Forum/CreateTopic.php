<?php

namespace App\Livewire\Forum;

use Livewire\Component;
use App\Models\Topic;

class CreateTopic extends Component
{
    public $title;
    public $content;

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required|min:10',
    ];

    // Menambahkan custom error messages
    protected $messages = [
        'title.required' => 'Judul topik tidak boleh kosong',
        'title.min' => 'Judul topik minimal 3 karakter',
        'content.required' => 'Isi topik tidak boleh kosong',
        'content.min' => 'Isi topik minimal 10 karakter'
    ];

    public function submit()
    {
        $validated = $this->validate();
        
        $topic = Topic::create([
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => auth()->id(),
        ]);
        
        session()->flash('status', 'success');
        session()->flash('message', 'Topik berhasil dibuat!');
        return redirect()->route('forum.index');
    }

    public function render()
    {
        return view('livewire.forum.create-topic')
            ->extends('layouts.dashboard-layout')
            ->section('content');
    }
}
