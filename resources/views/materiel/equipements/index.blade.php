@extends('layouts.app')
@section('title', 'Équipements')
@section('page_title', 'Parc matériel & engins')
@section('page_icon', 'fa-truck')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Matériel</li>
    <li class="active">Équipements</li>
@endsection
@section('page_actions')
    <x-btn href="{{ route('materiel.equipements.create') }}" variant="accent" icon="fa-plus" size="sm">Nouvel engin</x-btn>
@endsection

@section('contenu')
<x-card padding="compact" class="mb-3">
    <form method="GET" class="row g-2">
        <div class="col-md-4"><x-form.input name="search" placeholder="Rechercher…" icon="fa-search" :value="request('search')"/></div>
        <div class="col-md-3"><x-form.select name="statut" :options="['disponible'=>'Disponible','en_service'=>'En service','en_panne'=>'En panne','en_maintenance'=>'En maintenance']" :value="request('statut')" placeholder="Tous" :select2="false"/></div>
        <div class="col-md-2"><x-btn type="submit" variant="primary" icon="fa-filter" class="w-100">Filtrer</x-btn></div>
    </form>
</x-card>

<div class="row g-3">
    @forelse($equipements as $e)
        <div class="col-lg-4 col-md-6">
            <div class="projet-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="projet-code">{{ $e->code }}</div>
                    <x-badge :variant="match($e->statut){'disponible'=>'success','en_service'=>'info','en_panne'=>'danger','en_maintenance'=>'warning',default=>'default'}">{{ ucfirst(str_replace('_',' ',$e->statut)) }}</x-badge>
                </div>
                <h5 class="projet-title">{{ $e->nom }}</h5>
                <div class="projet-client"><i class="fas fa-tag"></i> {{ $e->marque }} {{ $e->modele }}</div>
                <div class="projet-location"><i class="fas fa-truck"></i> {{ $e->numero_immatriculation ?? '—' }}</div>
                <div class="projet-stats">
                    <div><div class="stat-mini-label">Compteur</div><div class="stat-mini-value">{{ number_format($e->compteur_heures_actuel, 1) }} h</div></div>
                    <div><div class="stat-mini-label">Coût horaire</div><div class="stat-mini-value">{{ number_format($e->cout_horaire, 0, ',', ' ') }}</div></div>
                </div>
                <a href="{{ route('materiel.equipements.show', $e) }}" class="projet-link">Voir la fiche <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    @empty
        <div class="col-12"><x-empty-state icon="fa-truck" title="Aucun équipement" message="Commencez par ajouter vos engins."/></div>
    @endforelse
</div>
<div class="mt-3">{{ $equipements->links() }}</div>

<style>
.projet-card{background:var(--btp-card-bg);border:1px solid var(--btp-border);border-radius:var(--btp-radius-lg);padding:20px;transition:all var(--btp-transition);height:100%;display:flex;flex-direction:column}
.projet-card:hover{transform:translateY(-3px);box-shadow:var(--btp-shadow-hover)}
.projet-code{font-size:.75rem;font-weight:800;color:var(--btp-accent)}
.projet-title{font-size:1.05rem;font-weight:700;margin-bottom:8px}
.projet-client,.projet-location{font-size:.82rem;color:var(--btp-muted);margin-bottom:4px}
.projet-stats{display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:12px;background:#f8f9fc;border-radius:var(--btp-radius);margin:14px 0}
.stat-mini-label{font-size:.7rem;color:var(--btp-muted);font-weight:600;text-transform:uppercase}
.stat-mini-value{font-size:.9rem;font-weight:700}
.projet-link{margin-top:auto;display:flex;align-items:center;justify-content:flex-end;font-size:.85rem;font-weight:700;color:var(--btp-accent);text-decoration:none}
</style>
@endsection