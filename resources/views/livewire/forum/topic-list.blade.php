@extends('layouts.dashboard-layout')

@section('title', 'Dashboard - Forum')
@section('content')
    <main>
        @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let status = "{{ session('status') }}";
                let message = "{{ session('message') }}";

                switch (status) {
                    case 'success':
                        toastr.success(message, "Success");
                        break;
                    case 'error':
                        toastr.error(message, "Error");
                        break;
                    default:
                        toastr.info(message, "Notification");
                }
            });
        </script>
    @endif
        <div class="w-full">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold">Forum Diskusi</h3>
                <a href="{{ route('topics.create') }}" wire:navigate
                    class="inline-flex justify-center items-center text-center font-medium text-base tracking-widest focus:outline-none focus-visible:outline-none transition ease-in-out duration-150 bg-pewterBlue ring-2
                    ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151]
                    hover:translate-y-0.5 hover:translate-x-0.5 py-2.5 px-4 rounded">Buat Topik
                    Baru</a>
            </div>

            <!-- Search Input menggunakan komponen yang sudah ada -->
            <div class="mb-6">
                <form wire:submit.prevent="setSearch($event.target.search.value)" class="mb-4 flex space-x-4">
                    <x-search-input 
                        name="search" 
                        placeholder="Cari topik diskusi..." 
                        :value="$search" 
                    />
                </form>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden ring-2 ring-gray-700">
                @if($search && $topics->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        Tidak ditemukan topik dengan kata kunci "{{ $search }}".
                    </div>
                @else
                    @forelse ($topics as $topic)
                        <div class="p-6 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="{{ route('topics.show', $topic) }}"
                                        class="text-xl font-semibold text-gray-600 hover:text-gray-800">
                                        {{ $topic->title }}
                                    </a>
                                    <p class="text-gray-500 text-sm mt-1">
                                        Diposting oleh {{ $topic->user->name }} • {{ $topic->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center text-gray-500">
                                        <div><i class="fa-solid fa-comment mr-1"></i></div>
                                        <div>{{ $topic->comments_count }}</div>
                                    </div>

                                    {{-- Like Button --}}
                                    <livewire:forum.like-button :model="$topic" :wire:key="'topic-like-'.$topic->id" />
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            Belum ada topik. Jadilah yang pertama memulai diskusi!
                        </div>
                    @endforelse
                @endif
            </div>

            <div class="mt-4">
                {{ $topics->links('pagination::tailwind') }}
            </div>
        </div>
    </main>
@endsection