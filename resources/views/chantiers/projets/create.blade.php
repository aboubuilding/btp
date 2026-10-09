@extends('layouts.app')
@section('title', 'Nouveau chantier')
@section('page_title', 'Créer un chantier')
@section('page_icon', 'fa-helmet-safety')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('projets.index') }}">Chantiers</a></li>
    <li class="active">Nouveau</li>
@endsection
@section('contenu')
<form method="POST" action="{{ route('projets.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <x-card title="Informations du chantier" icon="fa-info-circle">
                @include('chantiers.projets.partials._form', ['projet' => null])
            </x-card>
        </div>
        <div class="col-lg-4">
            <x-card title="Actions" icon="fa-cog">
                <div class="d-grid gap-2">
                    <x-btn type="submit" variant="accent" size="lg" icon="fa-check">Enregistrer</x-btn>
                    <x-btn href="{{ route('projets.index') }}" variant="ghost" icon="fa-times">Annuler</x-btn>
                </div>
            </x-card>
        </div>
    </div>
</form>
@endsection