@extends('layouts.app')
@section('title', 'Clients')
@section('page_title', 'Clients / Maîtres d\'ouvrage')
@section('page_icon', 'fa-users')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Commercial</li>
    <li class="active">Clients</li>
@endsection
@section('page_actions')
    <x-btn href="{{ route('commercial.clients.create') }}" variant="accent" icon="fa-plus" size="sm">Nouveau client</x-btn>
@endsection

@section('contenu')
<x-card padding="compact" class="mb-3">
    <form method="GET" class="row g-2">
        <div class="col-md-4"><x-form.input name="search" placeholder="Rechercher…" icon="fa-search" :value="request('search')"/></div>
        <div class="col-md-3"><x-form.select name="type" :options="['particulier'=>'Particulier','entreprise'=>'Entreprise','public'=>'Public']" :value="request('type')" placeholder="Tous les types" :select2="false"/></div>
        <div class="col-md-2"><x-btn type="submit" variant="primary" icon="fa-filter" class="w-100">Filtrer</x-btn></div>
    </form>
</x-card>

<x-table :headers="['Code','Raison sociale','Type','Contact','Téléphone','Chantiers','Actions']" :rows="$clients">
    @foreach($clients as $client)
        <tr>
            <td><span class="fw-700 text-btp-primary">CLI-{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</span></td>
            <td><div class="fw-600">{{ $client->nom }}</div><small class="text-btp-muted">{{ $client->email }}</small></td>
            <td><x-badge :variant="match($client->type){'entreprise'=>'info','public'=>'purple',default=>'default'}">{{ ucfirst($client->type) }}</x-badge></td>
            <td>{{ $client->contact ?? '—' }}</td>
            <td>{{ $client->telephone ?? '—' }}</td>
            <td><x-badge variant="accent">{{ $client->projets_count ?? 0 }}</x-badge></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('commercial.clients.show', $client) }}" class="btn-icon-sm"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('commercial.clients.edit', $client) }}" class="btn-icon-sm"><i class="fas fa-pen"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
</x-table>
<div class="mt-3">{{ $clients->links() }}</div>
@endsection