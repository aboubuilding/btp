@php
    $jalon = $jalon ?? new \App\Domain\Execution\Models\JalonProjet();
    $action = $jalon->exists ? route('projets.jalons.update', $jalon) : route('projets.jalons.store', $projet);
    $method = $jalon->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-flag-checkered"></i>
        <span>{{ $jalon->exists ? 'Modifier' : 'Nouveau jalon' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.input name="libelle" label="Libellé" :required="true" :value="$jalon->libelle" col="col-12"/>
            <x-form.input name="date_echeance" label="Échéance" type="date" :required="true" :value="$jalon->date_echeance?->format('Y-m-d')" col="col-md-6"/>
            <x-form.input name="date_atteinte" label="Date d'atteinte" type="date" :value="$jalon->date_atteinte?->format('Y-m-d')" col="col-md-6"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>