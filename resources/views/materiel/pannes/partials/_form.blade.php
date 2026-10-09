@php
    $panne = $panne ?? new \App\Domain\ParcMateriel\Models\PanneEquipement();
    $action = $panne->exists ? route('materiel.pannes.update', $panne) : route('materiel.pannes.store', $equipement);
    $method = $panne->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-triangle-exclamation"></i>
        <span>{{ $panne->exists ? 'Modifier' : 'Déclarer une panne' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.input name="date_panne" label="Date" type="date" :required="true"
                :value="$panne->date_panne?->format('Y-m-d') ?? now()->format('Y-m-d')" col="col-md-6"/>
            <x-form.input name="heures_immobilisation" label="Heures immob." type="number" :value="$panne->heures_immobilisation ?? 0" col="col-md-6"/>
            <x-form.textarea name="description" label="Description" :required="true" :value="$panne->description" :rows="3" col="col-12"/>
            <x-form.input name="cout_reparation" label="Coût réparation (FCFA)" type="number" step="0.01" :value="$panne->cout_reparation ?? 0" col="col-12"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-danger"><i class="fas fa-check"></i> Déclarer</button>
    </div>
</form>