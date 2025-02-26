<!-- resources/views/posts/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Liste des Publications</h1>
    <div>
        @foreach($posts as $post)
            <div>
                <h3>{{ $post->user->name }}</h3>
                <p>{{ $post->content }}</p>
                <p><strong>{{ $post->created_at }}</strong></p>
            </div>
        @endforeach
    </div>
@endsection
