@extends('layouts.app')
@section('title', 'Modifier '.$client->nom)
@section('page_title', 'Modifier : '.$client->nom)
@section('page_icon', 'fa-user-pen')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('commercial.clients.index') }}">Clients</a></li>
    <li class="active">{{ $client->nom }}</li>
@endsection
@section('contenu')
<form method="POST" action="{{ route('commercial.clients.update', $client) }}">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-lg-8">
            <x-card title="Informations" icon="fa-id-card">@include('commercial.clients.partials._form', ['client' => $client])</x-card>
        </div>
        <div class="col-lg-4">
            <x-card title="Actions" icon="fa-cog">
                <div class="d-grid gap-2">
                    <x-btn type="submit" variant="accent" size="lg" icon="fa-check">Mettre à jour</x-btn>
                    <x-btn href="{{ route('commercial.clients.show', $client) }}" variant="ghost" icon="fa-times">Annuler</x-btn>
                </div>
            </x-card>
        </div>
    </div>
</form>
@endsection