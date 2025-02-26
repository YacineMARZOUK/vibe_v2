<!-- resources/views/friends/request.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Envoyer une demande d'ami à {{ $user->name }}</h1>
    
    <form action="{{ route('friend.request', $user->id) }}" method="POST">
        @csrf
        <button type="submit">Envoyer la demande d'ami</button>
    </form>

    <a href="{{ url('/friends') }}">Voir mes amis</a>
@endsection
