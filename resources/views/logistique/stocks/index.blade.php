@extends('layouts.app')
@section('title', 'Niveaux de stock')
@section('page_title', 'Niveaux de stock')
@section('page_icon', 'fa-layer-group')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Logistique</li>
    <li class="active">Stocks</li>
@endsection
@section('page_actions')
    @include('layouts.partials._chantier-filter')
    <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-mvt-create"
            data-modal-url="{{ route('logistique.stocks.mouvement.create') }}">
        <i class="fas fa-right-left"></i><span>Nouveau mouvement</span>
    </button>
@endsection

@section('contenu')
<div class="row g-3 mb-3">
    <div class="col-lg-3 col-md-6"><x-stat-card label="Valeur totale" :value="number_format($stats['valeur_totale'] ?? 0, 0, ',', ' ').' FCFA'" icon="fa-coins" color="success"/></div>
    <div class="col-lg-3 col-md-6"><x-stat-card label="Articles en stock" :value="$stats['nb_articles'] ?? 0" icon="fa-cubes" color="info"/></div>
    <div class="col-lg-3 col-md-6"><x-stat-card label="Sous le seuil" :value="$stats['sous_seuil'] ?? 0" icon="fa-triangle-exclamation" color="danger"/></div>
    <div class="col-lg-3 col-md-6"><x-stat-card label="Mouvements ce mois" :value="$stats['mouvements_mois'] ?? 0" icon="fa-arrow-right-arrow-left" color="accent"/></div>
</div>

<x-table :headers="['Code','Matériau','Dépôt','Quantité','CMUP','Valeur','Statut']" :rows="$stocks">
    @foreach($stocks as $s)
        <tr>
            <td class="fw-700 text-btp-primary">{{ $s->materiau->code }}</td>
            <td><div class="fw-600">{{ $s->materiau->nom }}</div><small class="text-btp-muted">{{ $s->materiau->categorie?->nom }}</small></td>
            <td>{{ $s->entrepot->nom }}</td>
            <td class="fw-700">{{ number_format($s->quantite, 2, ',', ' ') }} {{ $s->materiau->unite }}</td>
            <td>{{ number_format($s->cmup, 0, ',', ' ') }} FCFA</td>
            <td class="fw-600">{{ number_format($s->valeur, 0, ',', ' ') }} FCFA</td>
            <td>
                @if($s->est_sous_seuil)<x-badge variant="danger" icon="fa-triangle-exclamation">Critique</x-badge>
                @elseif($s->quantite <= $s->materiau->seuil_alerte_stock_min * 2)<x-badge variant="warning">Faible</x-badge>
                @else<x-badge variant="success">OK</x-badge>@endif
            </td>
        </tr>
    @endforeach
</x-table>
@include('components.modal', ['id' => 'modal-mvt-create'])
@endsection