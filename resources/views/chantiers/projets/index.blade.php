@extends('layouts.app')
@section('title', 'Chantiers')
@section('page_title', 'Chantiers')
@section('page_icon', 'fa-helmet-safety')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li class="active">Chantiers</li>
@endsection
@section('page_actions')
    <x-btn href="{{ route('projets.create') }}" variant="accent" icon="fa-plus" size="sm">Nouveau chantier</x-btn>
@endsection

@section('contenu')
<x-card padding="compact" class="mb-3">
    <form method="GET" class="row g-2">
        <div class="col-md-4"><x-form.input name="search" placeholder="Rechercher…" icon="fa-search" :value="request('search')"/></div>
        <div class="col-md-3"><x-form.select name="statut" :options="['planifie'=>'Planifié','en_cours'=>'En cours','suspendu'=>'Suspendu','termine'=>'Terminé','annule'=>'Annulé']" :value="request('statut')" placeholder="Tous" :select2="false"/></div>
        <div class="col-md-3"><x-form.select name="type" :options="['batiment'=>'Bâtiment','route'=>'Route','ouvrage_art'=>'Ouvrage d\'art','terrassement'=>'Terrassement','reseau'=>'Réseaux']" :value="request('type')" placeholder="Tous" :select2="false"/></div>
        <div class="col-md-2"><x-btn type="submit" variant="primary" icon="fa-filter" class="w-100">Filtrer</x-btn></div>
    </form>
</x-card>

<div class="row g-3">
    @forelse($projets as $projet)
        <div class="col-lg-4 col-md-6">
            <div class="projet-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="projet-code">{{ $projet->code }}</div>
                    <x-badge :variant="match($projet->statut){'en_cours'=>'success','planifie'=>'info','suspendu'=>'warning','termine'=>'default','annule'=>'danger',default=>'default'}">{{ ucfirst(str_replace('_',' ',$projet->statut)) }}</x-badge>
                </div>
                <h5 class="projet-title">{{ $projet->nom }}</h5>
                <div class="projet-client"><i class="fas fa-building"></i> {{ $projet->client->nom ?? '—' }}</div>
                <div class="projet-location"><i class="fas fa-location-dot"></i> {{ $projet->ville ?? '—' }}</div>
                <div class="projet-progress">
                    <div class="d-flex justify-content-between mb-1"><small class="fw-600">Avancement</small><small class="fw-700 text-btp-accent">{{ $projet->pourcentage_avancement }}%</small></div>
                    <div class="progress progress-btp"><div class="progress-bar bg-btp-accent" style="width:{{ $projet->pourcentage_avancement }}%"></div></div>
                </div>
                <div class="projet-stats">
                    <div><div class="stat-mini-label">Budget prévu</div><div class="stat-mini-value">{{ number_format($projet->budget_prevu, 0, ',', ' ') }}</div></div>
                    <div><div class="stat-mini-label">Coût réel</div><div class="stat-mini-value {{ $projet->ecart_budget >= 0 ? 'text-btp-success' : 'text-btp-danger' }}">{{ number_format($projet->budget_reel, 0, ',', ' ') }}</div></div>
                </div>
                <a href="{{ route('projets.show', $projet) }}" class="projet-link">Voir la fiche <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    @empty
        <div class="col-12">
            <x-empty-state icon="fa-helmet-safety" title="Aucun chantier" message="Commencez par créer votre premier chantier."/>
        </div>
    @endforelse
</div>
<div class="mt-3">{{ $projets->links() }}</div>

<style>
.projet-card{background:var(--btp-card-bg);border:1px solid var(--btp-border);border-radius:var(--btp-radius-lg);padding:20px;transition:all var(--btp-transition);height:100%;display:flex;flex-direction:column}
.projet-card:hover{transform:translateY(-3px);box-shadow:var(--btp-shadow-hover);border-color:rgba(240,144,12,.3)}
.projet-code{font-size:.75rem;font-weight:800;color:var(--btp-accent);letter-spacing:.5px}
.projet-title{font-size:1.05rem;font-weight:700;color:var(--btp-ink);margin-bottom:8px;line-height:1.3}
.projet-client,.projet-location{font-size:.82rem;color:var(--btp-muted);margin-bottom:4px}
.projet-client i,.projet-location i{width:16px}
.projet-progress{margin:16px 0}
.projet-stats{display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:12px;background:#f8f9fc;border-radius:var(--btp-radius);margin-bottom:14px}
.stat-mini-label{font-size:.7rem;color:var(--btp-muted);font-weight:600;text-transform:uppercase}
.stat-mini-value{font-size:.9rem;font-weight:700}
.projet-link{margin-top:auto;display:flex;align-items:center;justify-content:flex-end;font-size:.85rem;font-weight:700;color:var(--btp-accent);text-decoration:none}
.dark-theme .projet-stats{background:#0d1117}
</style>
@endsection