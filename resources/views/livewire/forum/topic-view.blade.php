@extends('layouts.dashboard-layout')

@section('title', 'Dashboard - Topik Forum')
@section('content')
<main class="w-full">
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($topic)
        <div class="mb-6 w-fit">
            <a href="{{ route('forum.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Forum
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 ring-2 ring-gray-700">
            {{-- Bagian Topik --}}
            <div class="p-8">
                <div class="flex justify-between items-start mb-2">
                    <h1 class="text-3xl font-semibold text-gray-700">{{ $topic->title }}</h1>
                    
                    {{-- Tombol Suka --}}
                    <livewire:forum.like-button 
                        :model="$topic" 
                        :wire:key="'topic-like-'.$topic->id" 
                    />
                </div>
                
                <div class="flex items-center text-gray-500 text-sm mb-8">
                    <span>Ditulis oleh {{ $topic->user->name ?? 'Pengguna Tidak Dikenal' }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $topic->created_at->locale('id')->isoFormat('D MMMM Y') }}</span>
                </div>
                
                <div class="prose prose-lg max-w-none">
                    {!! nl2br(e($topic->content)) !!}
                </div>
            </div>
        </div>

        {{-- Bagian Komentar --}}
        <div class="mt-6">
            <h2 class="text-2xl font-semibold mb-2 text-gray-800">Komentar</h2>
            <livewire:forum.comment-list 
                :topic="$topic"
                class="mt-4" 
                :wire:key="'topic-comments-'.$topic->id" 
            />
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-xl shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-red-500 text-xl">Topik tidak ditemukan</p>
        </div>
    @endif
</main>
@endsection