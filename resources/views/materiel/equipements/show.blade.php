@extends('layouts.app')
@section('title', $equipement->nom)
@section('page_title', $equipement->code . ' — ' . $equipement->nom)
@section('page_icon', 'fa-truck')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('materiel.equipements.index') }}">Équipements</a></li>
    <li class="active">{{ $equipement->code }}</li>
@endsection

@section('contenu')
<ul class="nav nav-tabs-btp mb-4">
    <li><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-info"><i class="fas fa-info-circle"></i> Infos</button></li>
    <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-aff"><i class="fas fa-map-location-dot"></i> Affectations</button></li>
    <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pannes"><i class="fas fa-triangle-exclamation"></i> Pannes</button></li>
    <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-carb"><i class="fas fa-gas-pump"></i> Carburant</button></li>
    <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-docs"><i class="fas fa-file-lines"></i> Documents</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tab-info">
        <div class="row g-3">
            <div class="col-lg-3"><x-stat-card label="Compteur" :value="number_format($equipement->compteur_heures_actuel, 1).' h'" icon="fa-gauge" color="accent"/></div>
            <div class="col-lg-3"><x-stat-card label="Coût horaire" :value="number_format($equipement->cout_horaire, 0, ',', ' ').' FCFA'" icon="fa-coins" color="primary"/></div>
            <div class="col-lg-3"><x-stat-card label="Valeur actuelle" :value="number_format($equipement->valeur_actuelle, 0, ',', ' ').' FCFA'" icon="fa-chart-line" color="info"/></div>
            <div class="col-lg-3"><x-stat-card label="Statut" :value="$equipement->statut_label" icon="fa-signal" color="success"/></div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-aff">
        <x-card title="Affectations" icon="fa-map-location-dot">
            <x-slot:actions>
                <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-aff-create"
                        data-modal-url="{{ route('materiel.affectations.create', $equipement) }}">
                    <i class="fas fa-plus"></i><span>Nouvelle affectation</span>
                </button>
            </x-slot:actions>
            <x-table :headers="['Chantier','Début','Fin','Compteur début','Compteur fin','Statut']" :rows="$equipement->affectations">
                @foreach($equipement->affectations as $a)
                    <tr>
                        <td>{{ $a->projet?->code }}</td>
                        <td>{{ $a->date_debut->format('d/m/Y') }}</td>
                        <td>{{ $a->date_fin?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $a->compteur_debut }}</td>
                        <td>{{ $a->compteur_fin ?? '—' }}</td>
                        <td><x-badge :variant="$a->statut === 'ouverte' ? 'info' : 'default'">{{ ucfirst($a->statut) }}</x-badge></td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        @include('components.modal', ['id' => 'modal-aff-create'])
    </div>

    <div class="tab-pane fade" id="tab-pannes">
        <x-card title="Pannes" icon="fa-triangle-exclamation">
            <x-slot:actions>
                <button type="button" class="btn-btp btn-danger btn-sm" data-modal-open="modal-panne-create"
                        data-modal-url="{{ route('materiel.pannes.create', $equipement) }}">
                    <i class="fas fa-plus"></i><span>Déclarer une panne</span>
                </button>
            </x-slot:actions>
            <x-table :headers="['Date','Description','Heures immob.','Coût','Statut']" :rows="$equipement->pannes">
                @foreach($equipement->pannes as $p)
                    <tr>
                        <td>{{ $p->date_panne->format('d/m/Y') }}</td>
                        <td>{{ Str::limit($p->description, 50) }}</td>
                        <td>{{ $p->heures_immobilisation }} h</td>
                        <td class="fw-600">{{ number_format($p->cout_reparation, 0, ',', ' ') }} FCFA</td>
                        <td><x-badge :variant="$p->statut === 'cloturee' ? 'success' : 'danger'">{{ ucfirst($p->statut) }}</x-badge></td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        @include('components.modal', ['id' => 'modal-panne-create'])
    </div>

    <div class="tab-pane fade" id="tab-carb">
        <x-card title="Relevés carburant" icon="fa-gas-pump">
            <x-slot:actions>
                <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-carb-create"
                        data-modal-url="{{ route('materiel.carburant.create', $equipement) }}">
                    <i class="fas fa-plus"></i><span>Nouveau relevé</span>
                </button>
            </x-slot:actions>
            <x-table :headers="['Date','Qté','P.U.','Coût','Compteur']" :rows="$equipement->relevesCarburant">
                @foreach($equipement->relevesCarburant as $r)
                    <tr>
                        <td>{{ $r->date_releve->format('d/m/Y') }}</td>
                        <td>{{ $r->quantite }} L</td>
                        <td>{{ number_format($r->prix_unitaire, 0, ',', ' ') }}</td>
                        <td class="fw-600">{{ number_format($r->cout_total, 0, ',', ' ') }}</td>
                        <td>{{ $r->compteur }}</td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        @include('components.modal', ['id' => 'modal-carb-create'])
    </div>

    <div class="tab-pane fade" id="tab-docs">
        <x-card title="Documents" icon="fa-file-lines">
            <x-slot:actions>
                <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-doc-create"
                        data-modal-url="{{ route('materiel.documents.create', $equipement) }}">
                    <i class="fas fa-plus"></i><span>Ajouter</span>
                </button>
            </x-slot:actions>
            <x-table :headers="['Type','N°','Émission','Expiration','État']" :rows="$equipement->documents">
                @foreach($equipement->documents as $d)
                    <tr>
                        <td>{{ ucfirst(str_replace('_',' ',$d->type)) }}</td>
                        <td>{{ $d->numero }}</td>
                        <td>{{ $d->date_emission?->format('d/m/Y') }}</td>
                        <td>{{ $d->date_expiration?->format('d/m/Y') }}</td>
                        <td>
                            @if($d->est_expire)<x-badge variant="danger">Expiré</x-badge>
                            @elseif($d->expire_bientot)<x-badge variant="warning">Bientôt</x-badge>
                            @else<x-badge variant="success">OK</x-badge>@endif
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        @include('components.modal', ['id' => 'modal-doc-create'])
    </div>
</div>
@endsection