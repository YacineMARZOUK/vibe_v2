@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                    {{ __('Créer une nouvelle publication') }}
                </h2>
                
                <!-- Affichage des erreurs -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Affichage du message de succès -->
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- Formulaire d'ajout de publication -->
                <form action="{{ route('posts.create') }}" method="POST" enctype="multipart/form-data" id="post-form">
                    @csrf
                    
                    <!-- Content -->
                    <div class="mb-4">
                        <label for="content" class="block text-gray-700 font-bold mb-2">Contenu :</label>
                        <textarea id="content" name="content" rows="4" class="w-full p-2 border border-gray-300 rounded-lg" required>{{ old('content') }}</textarea>
                    </div>
                    
                    <!-- Image -->
                    <div class="mb-4">
                        <label for="image" class="block text-gray-700 font-bold mb-2">Image :</label>
                        <input type="file" id="image" name="image" class="w-full p-2 border border-gray-300 rounded-lg">
                    </div>
                    
                    <!-- Bouton Soumettre -->
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Publier
                    </button>
                </form>
                
                <!-- JavaScript pour débuggage -->
                <script>
                    document.getElementById('post-form').addEventListener('submit', function(e) {
                        console.log('Form submitted');
                        // Uncomment to debug: e.preventDefault();
                    });
                </script>
            </div>
        </div>
    </div>
@endsection