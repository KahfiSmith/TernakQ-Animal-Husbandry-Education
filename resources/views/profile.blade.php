@extends('layouts.dashboard-layout')

@section('title', 'Dashboard - Forum')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div>
        <div class="w-7/12 space-y-7">
            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700">
                <div class="w-full">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700">
                <div class="w-full">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700">
                <div class="w-full">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
@endsection
