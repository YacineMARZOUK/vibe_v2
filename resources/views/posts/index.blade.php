@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Affichage des messages de succès -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Affichage des erreurs -->
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Section des publications -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                    {{ __('Publications') }}
                </h2>

                @if($posts->count() > 0)
                    <div class="space-y-6">
                        @foreach($posts as $post)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="flex items-center mb-2">
                                    <div class="font-bold text-lg">{{ $post->user->name }}</div>
                                    <div class="text-gray-500 text-sm ml-2">
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>
                                    
                                    @if($post->user_id === auth()->id())
    <div class="ml-auto flex items-center space-x-2">
        <a href="{{ route('posts.edit', $post->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-150 ease-in-out text-sm font-medium">
            Modifier
        </a>
        <button onclick="document.getElementById('delete-form-{{ $post->id }}').submit();" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-150 ease-in-out text-sm font-medium">
            Supprimer
        </button>
        <form id="delete-form-{{ $post->id }}" action="{{ route('posts.destroy', $post->id) }}" method="POST" class="hidden" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication?');">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endif
                                </div>
                                
                                <div class="text-gray-800 mb-4">
                                    {{ $post->content }}
                                </div>
                                
                                @if($post->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Image de publication" class="rounded-lg max-h-96 w-auto">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        Aucune publication pour le moment.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection