@extends('layouts.app')
@section('title', $employe->nom_complet)
@section('page_title', $employe->nom_complet . ' — ' . $employe->matricule)
@section('page_icon', 'fa-id-badge')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('rh.employes.index') }}">Employés</a></li>
    <li class="active">{{ $employe->matricule }}</li>
@endsection
@section('page_actions')
    <x-btn variant="outline" size="sm" icon="fa-pen" href="{{ route('rh.employes.edit', $employe) }}">Modifier</x-btn>
@endsection

@section('contenu')
<div class="row g-3">
    <div class="col-lg-4">
        <x-card title="Fiche employé" icon="fa-id-card">
            <div style="display:grid;gap:12px">
                <div><small class="text-btp-muted text-uppercase fw-700 d-block">Poste</small><strong>{{ $employe->poste?->nom ?? '—' }}</strong></div>
                <div><small class="text-btp-muted text-uppercase fw-700 d-block">Département</small><strong>{{ $employe->departement?->nom ?? '—' }}</strong></div>
                <div><small class="text-btp-muted text-uppercase fw-700 d-block">Type contrat</small><strong>{{ strtoupper($employe->type_contrat) }}</strong></div>
                <div><small class="text-btp-muted text-uppercase fw-700 d-block">Téléphone</small><strong>{{ $employe->telephone ?? '—' }}</strong></div>
                <div><small class="text-btp-muted text-uppercase fw-700 d-block">Salaire base</small><strong>{{ number_format($employe->salaire_base, 0, ',', ' ') }} FCFA</strong></div>
            </div>
        </x-card>
    </div>
    <div class="col-lg-8">
        <ul class="nav nav-tabs-btp mb-3">
            <li><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-contrats"><i class="fas fa-file-signature"></i> Contrats</button></li>
            <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-conges"><i class="fas fa-umbrella-beach"></i> Congés</button></li>
            <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-bulletins"><i class="fas fa-file-invoice-dollar"></i> Bulletins</button></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-contrats">
                <x-card padding="compact">
                    <x-table :headers="['N°','Type','Début','Fin','Statut']" :rows="$employe->contrats">
                        @foreach($employe->contrats as $c)
                            <tr><td>{{ $c->numero }}</td><td>{{ strtoupper($c->type) }}</td><td>{{ $c->date_debut->format('d/m/Y') }}</td><td>{{ $c->date_fin?->format('d/m/Y') ?? '—' }}</td><td><x-badge :variant="$c->statut === 'en_cours' ? 'success' : 'default'">{{ ucfirst($c->statut) }}</x-badge></td></tr>
                        @endforeach
                    </x-table>
                </x-card>
            </div>
            <div class="tab-pane fade" id="tab-conges">
                <x-card padding="compact">
                    <x-table :headers="['Type','Début','Fin','Jours','Statut']" :rows="$employe->conges">
                        @foreach($employe->conges as $c)
                            <tr><td>{{ $c->typeConge?->nom }}</td><td>{{ $c->date_debut->format('d/m/Y') }}</td><td>{{ $c->date_fin->format('d/m/Y') }}</td><td>{{ $c->nombre_jours }}</td><td><x-badge variant="info">{{ $c->statut }}</x-badge></td></tr>
                        @endforeach
                    </x-table>
                </x-card>
            </div>
            <div class="tab-pane fade" id="tab-bulletins">
                <x-card padding="compact">
                    <x-table :headers="['Période','Brut','Net','Statut']" :rows="$employe->bulletins">
                        @foreach($employe->bulletins as $b)
                            <tr><td>{{ $b->periode?->libelle }}</td><td>{{ number_format($b->brut, 0, ',', ' ') }}</td><td class="fw-600">{{ number_format($b->net_a_payer, 0, ',', ' ') }}</td><td><x-badge variant="accent">{{ $b->statut }}</x-badge></td></tr>
                        @endforeach
                    </x-table>
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection