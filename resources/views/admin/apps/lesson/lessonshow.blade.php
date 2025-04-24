@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $formation->titre }}</h1>
        <p><strong>Description:</strong> {{ $formation->description }}</p>
        <p><strong>Durée:</strong> {{ $formation->duree }}</p>
        <p><strong>Type:</strong> {{ $formation->type }}</p>
        <p><strong>Prix:</strong> {{ $formation->prix }}</p>

        <p><strong>Catégorie:</strong> {{ $formation->categorie->titre }}</p>
        <a href="{{ route('formations') }}" class="btn btn-secondary">Retour à la lise </a>
    </div>
@endsection
