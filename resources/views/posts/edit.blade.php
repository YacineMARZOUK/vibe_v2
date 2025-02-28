@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier la publication</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="content" class="block text-gray-700 font-medium">Contenu</label>
                <textarea id="content" name="content" rows="4" class="w-full p-2 border rounded">{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-medium">Image</label>
                <input type="file" id="image" name="image" class="w-full p-2 border rounded">

                @if ($post->image)
                    <div class="mt-4">
                        <p class="text-gray-600">Image actuelle :</p>
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Image actuelle" class="rounded-lg max-h-64 w-auto">
                    </div>
                @endif
            </div>

            <div class="flex items-center space-x-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Mettre à jour</button>
                <a href="{{ route('posts.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">Annuler</a>
            </div>
        </form>
    </div>
@endsection
