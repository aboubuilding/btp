@extends('layouts.app')
@section('title', 'Incidents')
@section('page_title', 'Incidents & sécurité')
@section('page_icon', 'fa-triangle-exclamation')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>QHSE</li>
    <li class="active">Incidents</li>
@endsection
@section('page_actions')
    <x-btn href="{{ route('qhse.incidents.create') }}" variant="danger" icon="fa-plus" size="sm">Déclarer un incident</x-btn>
@endsection

@section('contenu')
<div class="row g-3 mb-3">
    <div class="col-lg-3"><x-stat-card label="Jours sans accident" value="47" icon="fa-shield-halved" color="success"/></div>
    <div class="col-lg-3"><x-stat-card label="Incidents ce mois" :value="$stats['incidents_mois'] ?? 0" icon="fa-triangle-exclamation" color="danger"/></div>
    <div class="col-lg-3"><x-stat-card label="Causeries" :value="$stats['causeries'] ?? 0" icon="fa-comments" color="info"/></div>
    <div class="col-lg-3"><x-stat-card label="Non-conformités" :value="$stats['non_conformites'] ?? 0" icon="fa-clipboard-check" color="warning"/></div>
</div>

<x-table :headers="['Date','Chantier','Type','Gravité','Victimes','Jours arrêt','Statut','Actions']" :rows="$incidents">
    @foreach($incidents as $i)
        <tr>
            <td>{{ $i->date_incident->format('d/m/Y H:i') }}</td>
            <td><a href="{{ route('projets.show', $i->projet) }}" class="text-decoration-none fw-600 text-btp-accent">{{ $i->projet?->code }}</a></td>
            <td>{{ ucfirst(str_replace('_',' ',$i->type)) }}</td>
            <td><x-badge :variant="match($i->gravite){'mortelle'=>'danger','grave'=>'danger','moyenne'=>'warning','mineure'=>'info',default=>'default'}">{{ ucfirst($i->gravite) }}</x-badge></td>
            <td>{{ $i->nombre_victimes }}</td>
            <td>{{ $i->jours_arret }}</td>
            <td><x-badge :variant="match($i->statut){'clos'=>'success','en_analyse'=>'warning',default=>'info'}">{{ ucfirst(str_replace('_',' ',$i->statut)) }}</x-badge></td>
            <td><a href="{{ route('qhse.incidents.show', $i) }}" class="btn-icon-sm"><i class="fas fa-eye"></i></a></td>
        </tr>
    @endforeach
</x-table>
<div class="mt-3">{{ $incidents->links() }}</div>
@endsection