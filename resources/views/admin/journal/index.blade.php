@extends('layouts.app')
@section('title', 'Journal')
@section('page_title', 'Journal d\'activités')
@section('page_icon', 'fa-history')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Admin</li>
    <li class="active">Journal</li>
@endsection

@section('contenu')
<x-card padding="compact" class="mb-3">
    <form method="GET" class="row g-2">
        <div class="col-md-4"><x-form.input name="search" placeholder="Rechercher…" icon="fa-search" :value="request('search')"/></div>
        <div class="col-md-3"><x-form.select name="action" :options="['create'=>'Création','update'=>'Modification','validate'=>'Validation','delete'=>'Suppression']" :value="request('action')" placeholder="Toutes" :select2="false"/></div>
        <div class="col-md-2"><x-btn type="submit" variant="primary" icon="fa-filter" class="w-100">Filtrer</x-btn></div>
    </form>
</x-card>

<x-table :headers="['Date','Utilisateur','Action','Objet','IP']" :rows="$logs">
    @foreach($logs as $l)
        <tr>
            <td><small>{{ $l->date_action?->format('d/m/Y H:i:s') }}</small></td>
            <td>{{ $l->user?->nom ?? 'Système' }}</td>
            <td><x-badge :variant="match($l->action){'create'=>'success','update'=>'info','validate'=>'accent','delete'=>'danger',default=>'default'}">{{ $l->action }}</x-badge></td>
            <td><small>{{ class_basename($l->objet_type) }} #{{ $l->objet_id }}</small></td>
            <td><small class="text-btp-muted">{{ $l->ip }}</small></td>
        </tr>
    @endforeach
</x-table>
<div class="mt-3">{{ $logs->links() }}</div>
@endsection