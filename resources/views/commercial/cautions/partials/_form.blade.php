@php
    $caution = $caution ?? new \App\Domain\Commercial\Models\CautionMarche();
    $action = $caution->exists ? route('commercial.caution.update', $caution) : route('commercial.caution.store', $marche);
    $method = $caution->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-shield-halved"></i>
        <span>{{ $caution->exists ? 'Modifier' : 'Nouvelle caution' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.select name="type" label="Type" :required="true" :value="$caution->type" col="col-md-6"
                :options="['soumission'=>'Soumission','avance'=>'Avance','bonne_execution'=>'Bonne exécution']"/>
            <x-form.input name="banque" label="Banque" icon="fa-building-columns" :value="$caution->banque" col="col-md-6"/>
            <x-form.input name="montant" label="Montant (FCFA)" type="number" step="0.01" :required="true" :value="$caution->montant" col="col-12"/>
            <x-form.input name="date_emission" label="Émission" type="date" :value="$caution->date_emission?->format('Y-m-d')" col="col-md-6"/>
            <x-form.input name="date_echeance" label="Échéance" type="date" :value="$caution->date_echeance?->format('Y-m-d')" col="col-md-6"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>