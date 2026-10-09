@extends('layouts.app')
@section('title', 'Employés')
@section('page_title', 'Employés')
@section('page_icon', 'fa-id-badge')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>RH</li>
    <li class="active">Employés</li>
@endsection
@section('page_actions')
    <x-btn href="{{ route('rh.employes.create') }}" variant="accent" icon="fa-user-plus" size="sm">Nouvel employé</x-btn>
@endsection

@section('contenu')
<x-card padding="compact" class="mb-3">
    <form method="GET" class="row g-2">
        <div class="col-md-4"><x-form.input name="search" placeholder="Rechercher…" icon="fa-search" :value="request('search')"/></div>
        <div class="col-md-3"><x-form.select name="type_contrat" :options="['cdi'=>'CDI','cdd'=>'CDD','journalier'=>'Journalier','stage'=>'Stage']" :value="request('type_contrat')" placeholder="Tous" :select2="false"/></div>
        <div class="col-md-2"><x-btn type="submit" variant="primary" icon="fa-filter" class="w-100">Filtrer</x-btn></div>
    </form>
</x-card>

<x-table :headers="['Matricule','Nom','Poste','Département','Contrat','Statut','Actions']" :rows="$employes">
    @foreach($employes as $e)
        <tr>
            <td class="fw-700 text-btp-primary">{{ $e->matricule }}</td>
            <td><div class="fw-600">{{ $e->nom_complet }}</div><small class="text-btp-muted">{{ $e->telephone }}</small></td>
            <td>{{ $e->poste?->nom ?? '—' }}</td>
            <td>{{ $e->departement?->nom ?? '—' }}</td>
            <td><x-badge variant="accent">{{ strtoupper($e->type_contrat) }}</x-badge></td>
            <td><x-badge :variant="$e->statut === 'actif' ? 'success' : 'default'" dot>{{ ucfirst($e->statut) }}</x-badge></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('rh.employes.show', $e) }}" class="btn-icon-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('rh.employes.edit', $e) }}" class="btn-icon-sm"><i class="fas fa-pen"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
</x-table>
<div class="mt-3">{{ $employes->links() }}</div>
@endsection