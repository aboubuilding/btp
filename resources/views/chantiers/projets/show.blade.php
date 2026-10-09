@extends('layouts.app')
@section('title', $projet->nom)
@section('page_title', $projet->code . ' — ' . $projet->nom)
@section('page_icon', 'fa-helmet-safety')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('projets.index') }}">Chantiers</a></li>
    <li class="active">{{ $projet->code }}</li>
@endsection
@section('page_actions')
    <x-btn variant="outline" icon="fa-pen" size="sm" href="{{ route('projets.edit', $projet) }}">Modifier</x-btn>
    <x-btn variant="accent" icon="fa-chart-gantt" size="sm" href="{{ route('projets.planning.gantt', $projet) }}">Planning Gantt</x-btn>
@endsection

@section('contenu')
<ul class="nav nav-tabs-btp mb-4">
    <li><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-overview"><i class="fas fa-info-circle"></i> Vue d'ensemble</button></li>
    <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-planning"><i class="fas fa-calendar"></i> Planning</button></li>
    <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-journal"><i class="fas fa-book"></i> Journal</button></li>
    <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-equipe"><i class="fas fa-users"></i> Équipe</button></li>
    <li><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-budget"><i class="fas fa-sack-dollar"></i> Budget</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tab-overview">
        <div class="row g-3">
            <div class="col-lg-8">
                <x-card title="Informations générales" icon="fa-circle-info">
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px">
                        <div><span class="text-btp-muted small d-block text-uppercase fw-700">Code</span><span class="fw-600">{{ $projet->code }}</span></div>
                        <div><span class="text-btp-muted small d-block text-uppercase fw-700">Type</span><span class="fw-600">{{ ucfirst(str_replace('_',' ',$projet->type)) }}</span></div>
                        <div><span class="text-btp-muted small d-block text-uppercase fw-700">Client</span><span class="fw-600">{{ $projet->client->nom ?? '—' }}</span></div>
                        <div><span class="text-btp-muted small d-block text-uppercase fw-700">Ville</span><span class="fw-600">{{ $projet->ville }}</span></div>
                        <div><span class="text-btp-muted small d-block text-uppercase fw-700">Conducteur</span><span class="fw-600">{{ $projet->conducteur->nom_complet ?? '—' }}</span></div>
                        <div><span class="text-btp-muted small d-block text-uppercase fw-700">Chef de chantier</span><span class="fw-600">{{ $projet->chefChantier->nom_complet ?? '—' }}</span></div>
                        <div><span class="text-btp-muted small d-block text-uppercase fw-700">Début prévu</span><span class="fw-600">{{ $projet->date_debut_prevue?->format('d/m/Y') }}</span></div>
                        <div><span class="text-btp-muted small d-block text-uppercase fw-700">Fin prévue</span><span class="fw-600">{{ $projet->date_fin_prevue?->format('d/m/Y') }}</span></div>
                    </div>
                </x-card>
            </div>
            <div class="col-lg-4">
                <x-card title="Avancement" icon="fa-chart-pie">
                    <div class="text-center py-3">
                        <div style="position:relative;display:inline-block">
                            <svg viewBox="0 0 120 120" width="160" height="160">
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#f0900c" stroke-width="8"
                                    stroke-dasharray="339.292" stroke-dashoffset="{{ 339.292 * (1 - $projet->pourcentage_avancement/100) }}"
                                    stroke-linecap="round" transform="rotate(-90 60 60)"/>
                            </svg>
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center">
                                <div style="font-size:1.8rem;font-weight:800;color:var(--btp-ink)">{{ $projet->pourcentage_avancement }}%</div>
                                <div style="font-size:.72rem;color:var(--btp-muted);font-weight:600;text-transform:uppercase">Avancement</div>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-planning">
        <x-card title="Phases" icon="fa-layer-group" class="mb-3">
            <x-slot:actions>
                <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-phase-create"
                        data-modal-url="{{ route('projets.phases.create', $projet) }}">
                    <i class="fas fa-plus"></i><span>Nouvelle phase</span>
                </button>
            </x-slot:actions>
            <x-table :headers="['Ordre','Nom','Début','Fin','Statut','']" :rows="$projet->phases">
                @foreach($projet->phases as $phase)
                    <tr>
                        <td>{{ $phase->ordre }}</td>
                        <td class="fw-600">{{ $phase->nom }}</td>
                        <td>{{ $phase->date_debut?->format('d/m/Y') }}</td>
                        <td>{{ $phase->date_fin?->format('d/m/Y') }}</td>
                        <td><x-badge :variant="$phase->statut === 'termine' ? 'success' : 'info'">{{ ucfirst(str_replace('_',' ',$phase->statut)) }}</x-badge></td>
                        <td><button class="btn-icon-sm" data-modal-open="modal-phase-edit-{{ $phase->id }}" data-modal-url="{{ route('projets.phases.edit', $phase) }}"><i class="fas fa-pen"></i></button></td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        <x-card title="Jalons" icon="fa-flag-checkered">
            <x-slot:actions>
                <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-jalon-create"
                        data-modal-url="{{ route('projets.jalons.create', $projet) }}">
                    <i class="fas fa-plus"></i><span>Nouveau jalon</span>
                </button>
            </x-slot:actions>
            <x-table :headers="['Libellé','Échéance','Atteint','Statut','']" :rows="$projet->jalons">
                @foreach($projet->jalons as $jalon)
                    <tr>
                        <td class="fw-600">{{ $jalon->libelle }}</td>
                        <td>{{ $jalon->date_echeance?->format('d/m/Y') }}</td>
                        <td>{{ $jalon->date_atteinte?->format('d/m/Y') ?? '—' }}</td>
                        <td><x-badge :variant="$jalon->est_atteint ? 'success' : ($jalon->est_manque ? 'danger' : 'warning')">{{ $jalon->statut_label }}</x-badge></td>
                        <td><button class="btn-icon-sm" data-modal-open="modal-jalon-edit-{{ $jalon->id }}" data-modal-url="{{ route('projets.jalons.edit', $jalon) }}"><i class="fas fa-pen"></i></button></td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        @include('components.modal', ['id' => 'modal-phase-create'])
        @foreach($projet->phases as $p) @include('components.modal', ['id' => "modal-phase-edit-{$p->id}"]) @endforeach
        @include('components.modal', ['id' => 'modal-jalon-create'])
        @foreach($projet->jalons as $j) @include('components.modal', ['id' => "modal-jalon-edit-{$j->id}"]) @endforeach
    </div>

    <div class="tab-pane fade" id="tab-equipe">
        <x-card title="Équipe affectée" icon="fa-users">
            <x-slot:actions>
                <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-equipe-create"
                        data-modal-url="{{ route('projets.equipe.create', $projet) }}">
                    <i class="fas fa-user-plus"></i><span>Affecter</span>
                </button>
            </x-slot:actions>
            <x-table :headers="['Employé','Poste','Rôle','Début','Fin','']" :rows="$projet->equipe">
                @foreach($projet->equipe as $m)
                    <tr>
                        <td class="fw-600">{{ $m->nom_complet }}</td>
                        <td>{{ $m->poste?->nom }}</td>
                        <td>{{ $m->pivot->role_chantier }}</td>
                        <td>{{ \Carbon\Carbon::parse($m->pivot->date_debut)->format('d/m/Y') }}</td>
                        <td>{{ $m->pivot->date_fin ? \Carbon\Carbon::parse($m->pivot->date_fin)->format('d/m/Y') : '—' }}</td>
                        <td><button class="btn-icon-sm text-btp-danger" data-confirm="Retirer cet employé ?"><i class="fas fa-user-minus"></i></button></td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
        @include('components.modal', ['id' => 'modal-equipe-create'])
    </div>

    <div class="tab-pane fade" id="tab-budget">
        <x-card title="Lignes budgétaires" icon="fa-sack-dollar">
            <x-table :headers="['Poste','Budget prévu','Réel','Écart','%']" :rows="$projet->ligneBudgets">
                @foreach($projet->ligneBudgets as $l)
                    <tr>
                        <td class="fw-600">{{ $l->libelle }}</td>
                        <td>{{ number_format($l->montant_prevu, 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($l->montant_reel, 0, ',', ' ') }} FCFA</td>
                        <td class="{{ $l->ecart >= 0 ? 'text-btp-success' : 'text-btp-danger' }} fw-600">{{ number_format($l->ecart, 0, ',', ' ') }} FCFA</td>
                        <td><x-badge :variant="$l->taux_consommation > 90 ? 'danger' : 'success'">{{ $l->taux_consommation }}%</x-badge></td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
    </div>
</div>
@endsection