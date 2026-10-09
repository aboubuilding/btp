@extends('layouts.app')
@section('title', 'Factures')
@section('page_title', 'Factures')
@section('page_icon', 'fa-file-invoice-dollar')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Finances</li>
    <li class="active">Factures</li>
@endsection
@section('page_actions')
    @include('layouts.partials._chantier-filter')
    <x-btn href="{{ route('finances.factures.create') }}" variant="accent" icon="fa-plus" size="sm">Nouvelle facture</x-btn>
@endsection

@section('contenu')
<div class="row g-3 mb-3">
    <div class="col-lg-3"><x-stat-card label="Factures clients" :value="number_format($stats['factures_clients'], 0, ',', ' ').' FCFA'" icon="fa-arrow-down" color="success"/></div>
    <div class="col-lg-3"><x-stat-card label="Factures fournisseurs" :value="number_format($stats['factures_fournisseurs'], 0, ',', ' ').' FCFA'" icon="fa-arrow-up" color="danger"/></div>
    <div class="col-lg-3"><x-stat-card label="Impayées" :value="number_format($stats['impayees'], 0, ',', ' ').' FCFA'" icon="fa-clock" color="warning"/></div>
    <div class="col-lg-3"><x-stat-card label="Encaissé ce mois" :value="number_format($stats['encaisse_mois'], 0, ',', ' ').' FCFA'" icon="fa-check-circle" color="accent"/></div>
</div>

<x-table :headers="['N°','Type','Tiers','Chantier','TTC','Échéance','Statut','Actions']" :rows="$factures">
    @foreach($factures as $f)
        <tr>
            <td class="fw-700 text-btp-primary">{{ $f->numero_facture }}</td>
            <td><x-badge :variant="$f->type === 'client' ? 'success' : 'danger'">{{ ucfirst($f->type) }}</x-badge></td>
            <td>{{ $f->facturable?->nom ?? '—' }}</td>
            <td>@if($f->projet)<a href="{{ route('projets.show', $f->projet) }}" class="text-decoration-none text-btp-accent fw-600">{{ $f->projet->code }}</a>@else—@endif</td>
            <td class="fw-600">{{ number_format($f->montant_ttc, 0, ',', ' ') }} FCFA</td>
            <td>
                <div>{{ $f->date_echeance?->format('d/m/Y') }}</div>
                @if($f->est_en_retard)<small class="text-btp-danger fw-600"><i class="fas fa-exclamation-triangle"></i> En retard</small>@endif
            </td>
            <td><x-badge :variant="match($f->statut){'payee'=>'success','partiellement_payee'=>'warning','emise'=>'info','annulee'=>'danger',default=>'default'}">{{ ucfirst(str_replace('_',' ',$f->statut)) }}</x-badge></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('finances.factures.show', $f) }}" class="btn-icon-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('finances.factures.pdf', $f) }}" class="btn-icon-sm text-btp-danger"><i class="fas fa-file-pdf"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
</x-table>
<div class="mt-3">{{ $factures->links() }}</div>
@endsection