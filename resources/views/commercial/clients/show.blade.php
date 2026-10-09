@extends('layouts.app')
@section('title', $client->nom)
@section('page_title', $client->nom)
@section('page_icon', 'fa-user')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('commercial.clients.index') }}">Clients</a></li>
    <li class="active">{{ $client->nom }}</li>
@endsection
@section('page_actions')
    <x-btn href="{{ route('commercial.clients.edit', $client) }}" variant="outline" size="sm" icon="fa-pen">Modifier</x-btn>
@endsection
@section('contenu')
<div class="row g-3">
    <div class="col-lg-4">
        <x-card title="Fiche client" icon="fa-id-card">
            <div style="display:grid;gap:14px">
                <div><span class="text-btp-muted small d-block text-uppercase fw-700">Type</span><span class="fw-600">{{ $client->type_label }}</span></div>
                <div><span class="text-btp-muted small d-block text-uppercase fw-700">Contact</span><span class="fw-600">{{ $client->contact ?? '—' }}</span></div>
                <div><span class="text-btp-muted small d-block text-uppercase fw-700">Téléphone</span><span class="fw-600">{{ $client->telephone ?? '—' }}</span></div>
                <div><span class="text-btp-muted small d-block text-uppercase fw-700">Email</span><span class="fw-600">{{ $client->email ?? '—' }}</span></div>
                <div><span class="text-btp-muted small d-block text-uppercase fw-700">NIF</span><span class="fw-600">{{ $client->nif ?? '—' }}</span></div>
            </div>
        </x-card>
    </div>
    <div class="col-lg-8">
        <ul class="nav nav-tabs-btp mb-3">
            <li><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-chantiers"><i class="fas fa-helmet-safety"></i> Chantiers ({{ $client->projets->count() }})</button></li>
            <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-factures"><i class="fas fa-file-invoice"></i> Factures ({{ $client->factures->count() }})</button></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-chantiers">
                <x-card padding="compact">
                    <x-table :headers="['Code','Nom','Statut','Avancement']" :rows="$client->projets">
                        @foreach($client->projets as $p)
                            <tr>
                                <td class="fw-700 text-btp-primary">{{ $p->code }}</td>
                                <td class="fw-600">{{ $p->nom }}</td>
                                <td><x-badge variant="success">{{ $p->statut }}</x-badge></td>
                                <td>{{ $p->pourcentage_avancement }}%</td>
                            </tr>
                        @endforeach
                    </x-table>
                </x-card>
            </div>
            <div class="tab-pane fade" id="tab-factures">
                <x-card padding="compact">
                    <x-table :headers="['N°','Montant TTC','Statut']" :rows="$client->factures">
                        @foreach($client->factures as $f)
                            <tr><td class="fw-700">{{ $f->numero_facture }}</td><td>{{ number_format($f->montant_ttc, 0, ',', ' ') }} FCFA</td><td><x-badge variant="info">{{ $f->statut }}</x-badge></td></tr>
                        @endforeach
                    </x-table>
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection