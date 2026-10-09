@php
    $phase = $phase ?? new \App\Domain\Execution\Models\PhaseProjet();
    $action = $phase->exists ? route('projets.phases.update', $phase) : route('projets.phases.store', $projet);
    $method = $phase->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-layer-group"></i>
        <span>{{ $phase->exists ? 'Modifier' : 'Nouvelle phase' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.input name="nom" label="Nom" icon="fa-tag" :required="true" :value="$phase->nom" col="col-md-8"/>
            <x-form.input name="ordre" label="Ordre" type="number" :value="$phase->ordre ?? 0" col="col-md-4"/>
            <x-form.input name="date_debut" label="Début" type="date" :value="$phase->date_debut?->format('Y-m-d')" col="col-md-6"/>
            <x-form.input name="date_fin" label="Fin" type="date" :value="$phase->date_fin?->format('Y-m-d')" col="col-md-6"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>