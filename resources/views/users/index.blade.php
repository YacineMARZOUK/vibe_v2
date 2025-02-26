@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Messages de notification -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Liste des utilisateurs -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                    {{ __('Utilisateurs') }}
                </h2>

                @if($users->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($users as $user)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="font-bold text-lg">{{ $user->name }}</div>
                                    
                                    <div>
                                        @if(isset($friendshipStatus[$user->id]))
                                            @if($friendshipStatus[$user->id]['status'] === 'pending')
                                                @if($friendshipStatus[$user->id]['sender'])
                                                    <span class="px-3 py-1 bg-yellow-500 text-white rounded text-sm">Demande envoyée</span>
                                                @else
                                                    <div class="flex space-x-2">
                                                        <form action="{{ route('friend.respond', [$friendshipStatus[$user->id]['id'], 'accept']) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition duration-150 ease-in-out text-sm font-medium">
                                                                Accepter
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('friend.respond', [$friendshipStatus[$user->id]['id'], 'reject']) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-150 ease-in-out text-sm font-medium">
                                                                Refuser
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @elseif($friendshipStatus[$user->id]['status'] === 'accepted')
                                                <span class="px-3 py-1 bg-green-500 text-white rounded text-sm">Ami</span>
                                            @elseif($friendshipStatus[$user->id]['status'] === 'rejected')
                                                <span class="px-3 py-1 bg-gray-500 text-white rounded text-sm">Demande rejetée</span>
                                            @endif
                                        @else
                                            <form action="{{ route('friend.request', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-150 ease-in-out text-sm font-medium">
                                                    Ajouter comme ami
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-gray-600 text-sm">
                                    {{ $user->email }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        Aucun utilisateur disponible.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection