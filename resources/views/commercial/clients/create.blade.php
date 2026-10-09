@extends('layouts.app')
@section('title', 'Nouveau client')
@section('page_title', 'Créer un client')
@section('page_icon', 'fa-user-plus')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('commercial.clients.index') }}">Clients</a></li>
    <li class="active">Nouveau</li>
@endsection
@section('contenu')
<form method="POST" action="{{ route('commercial.clients.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <x-card title="Informations du client" icon="fa-id-card">
                @include('commercial.clients.partials._form', ['client' => null])
            </x-card>
        </div>
        <div class="col-lg-4">
            <x-card title="Actions" icon="fa-cog">
                <div class="d-grid gap-2">
                    <x-btn type="submit" variant="accent" size="lg" icon="fa-check">Enregistrer</x-btn>
                    <x-btn href="{{ route('commercial.clients.index') }}" variant="ghost" icon="fa-times">Annuler</x-btn>
                </div>
            </x-card>
        </div>
    </div>
</form>
@endsection