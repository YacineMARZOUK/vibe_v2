<!-- resources/views/friends/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-semibold mb-6">Mes amis</h1>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($friends as $friendship)
                        <div class="bg-gray-50 p-4 rounded-lg shadow">
                            @php
                                $friend = $friendship->user_id === auth()->id() 
                                    ? $friendship->receiver 
                                    : $friendship->sender;
                            @endphp
                            <div class="font-medium text-lg mb-2">{{ $friend->name }}</div>
                            <div class="text-gray-600 text-sm mb-2">{{ $friend->email }}</div>
                            <div class="text-gray-500 text-xs">
                                Amis depuis {{ $friendship->updated_at->format('d/m/Y') }}
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center p-6 bg-gray-100 rounded-lg">
                            <p>Vous n'avez pas encore d'amis.</p>
                            <a href="{{ route('users.index') }}" class="mt-2 inline-block text-blue-600 hover:underline">
                                Trouver des amis
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection