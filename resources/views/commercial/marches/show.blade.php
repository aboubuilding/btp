@extends('layouts.app')
@section('title', $marche->reference)
@section('page_title', 'Marché ' . $marche->reference)
@section('page_icon', 'fa-file-signature')
@section('contenu')
<div class="row g-3 mb-3">
    <div class="col-lg-3"><x-stat-card label="Montant initial" :value="number_format($marche->montant_initial, 0, ',', ' ').' FCFA'" icon="fa-coins" color="primary"/></div>
    <div class="col-lg-3"><x-stat-card label="Montant actualisé" :value="number_format($marche->montant_actualise, 0, ',', ' ').' FCFA'" icon="fa-chart-line" color="accent"/></div>
    <div class="col-lg-3"><x-stat-card label="Taux avance" :value="$marche->taux_avance.'%'" icon="fa-hand-holding-dollar" color="info"/></div>
    <div class="col-lg-3"><x-stat-card label="Retenue garantie" :value="$marche->taux_retenue_garantie.'%'" icon="fa-shield-halved" color="warning"/></div>
</div>

<x-card title="Cautions bancaires" icon="fa-shield-halved" class="mb-3">
    <x-slot:actions>
        <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-caution-create"
                data-modal-url="{{ route('commercial.caution.create', $marche) }}">
            <i class="fas fa-plus"></i><span>Nouvelle caution</span>
        </button>
    </x-slot:actions>
    <x-table :headers="['Type','Banque','Montant','Échéance','Statut','']" :rows="$marche->cautions">
        @foreach($marche->cautions as $c)
            <tr>
                <td>{{ ucfirst(str_replace('_',' ',$c->type)) }}</td>
                <td>{{ $c->banque }}</td>
                <td class="fw-600">{{ number_format($c->montant, 0, ',', ' ') }} FCFA</td>
                <td>{{ $c->date_echeance?->format('d/m/Y') }}</td>
                <td><x-badge :variant="$c->statut === 'active' ? 'success' : 'default'">{{ ucfirst($c->statut) }}</x-badge></td>
                <td>
                    <button class="btn-icon-sm" data-modal-open="modal-caution-edit-{{ $c->id }}"
                            data-modal-url="{{ route('commercial.caution.edit', $c) }}">
                        <i class="fas fa-pen"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </x-table>
    @include('components.modal', ['id' => 'modal-caution-create'])
    @foreach($marche->cautions as $c)
        @include('components.modal', ['id' => "modal-caution-edit-{$c->id}"])
    @endforeach
</x-card>

<x-card title="Avenants" icon="fa-file-circle-plus">
    <x-slot:actions>
        <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-avenant-create"
                data-modal-url="{{ route('commercial.avenant.create', $marche) }}">
            <i class="fas fa-plus"></i><span>Nouvel avenant</span>
        </button>
    </x-slot:actions>
    <x-table :headers="['N°','Objet','Montant','Signé le','']" :rows="$marche->avenants">
        @foreach($marche->avenants as $a)
            <tr>
                <td class="fw-700">{{ $a->numero }}</td>
                <td>{{ Str::limit($a->objet, 40) }}</td>
                <td class="fw-600">{{ number_format($a->montant, 0, ',', ' ') }} FCFA</td>
                <td>{{ $a->date_signature?->format('d/m/Y') ?? '—' }}</td>
                <td>
                    <button class="btn-icon-sm" data-modal-open="modal-avenant-edit-{{ $a->id }}"
                            data-modal-url="{{ route('commercial.avenant.edit', $a) }}">
                        <i class="fas fa-pen"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </x-table>
    @include('components.modal', ['id' => 'modal-avenant-create'])
    @foreach($marche->avenants as $a)
        @include('components.modal', ['id' => "modal-avenant-edit-{$a->id}"])
    @endforeach
</x-card>
@endsection