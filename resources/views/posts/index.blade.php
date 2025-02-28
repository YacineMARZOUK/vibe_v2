@extends('layouts.app')

@section('content')
@if($posts->count() > 0)
    <!-- Your existing post display code -->
@else
    <div class="text-center py-4 text-gray-500">
        Aucune publication de vos amis pour le moment.
    </div>
@endif
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
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

        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Publications') }}</h1>

        @if($posts->count() > 0)
            <div class="space-y-6">
                @foreach($posts as $post)
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <span class="font-bold text-lg">{{ $post->user->name }}</span>
                            <span class="text-gray-500 text-sm ml-2">{{ $post->created_at->diffForHumans() }}</span>

                            @if($post->user_id === auth()->id())
                                <div class="ml-auto flex items-center space-x-2">
                                    <a href="{{ route('posts.edit', $post->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-sm">Modifier</a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="hidden" id="delete-form-{{ $post->id }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button onclick="document.getElementById('delete-form-{{ $post->id }}').submit();" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm">Supprimer</button>
                                </div>
                            @endif
                        </div>
                        <p class="text-gray-800 mb-4">{{ $post->content }}</p>
                        
                        @if($post->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Image de publication" class="rounded-lg max-h-96 w-auto">
                            </div>
                        @endif
                        <!-- Add this just after the post content and before the comments section -->
<div class="flex items-center mt-4 mb-2">
    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="mr-4">
        @csrf
        <button type="submit" class="flex items-center">
            @if($post->likes->where('user_id', auth()->id())->count() > 0)
                <!-- User has liked the post -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                </svg>
            @else
                <!-- User hasn't liked the post -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                </svg>
            @endif
            <span class="ml-1">{{ $post->likes->count() }}</span>
        </button>
    </form>
    
    <span class="text-gray-500 text-sm">{{ $post->comments->count() }} commentaires</span>
</div>

                        <div class="mt-6">
                            <h5 class="font-semibold text-lg">Commentaires ({{ $post->comments->count() }})</h5>

                            @if($post->comments->count() > 0)
                                <div class="space-y-4 mt-4">
                                    @foreach($post->comments as $comment)
                                        <div class="p-4 bg-gray-100 rounded">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="font-semibold">{{ $comment->user->name }}</span>
                                                <span class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</span>
                                                @if($comment->user_id === auth()->id())
                                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 text-sm">Supprimer</button>
                                                    </form>
                                                @endif
                                            </div>
                                            <p class="text-gray-700">{{ $comment->content }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 mt-2">Pas encore de commentaires.</p>
                            @endif

                            @auth
                                <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-4">
                                    @csrf
                                    <textarea name="content" class="w-full p-2 border rounded" rows="2" placeholder="Ajouter un commentaire..."></textarea>
                                    <button type="submit" class="mt-2 px-4 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Commenter</button>
                                </form>
                            @else
                                <p class="text-gray-500 mt-2">Connectez-vous pour commenter.</p>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-gray-500">Aucune publication pour le moment.</div>
        @endif
    </div>
@endsection
