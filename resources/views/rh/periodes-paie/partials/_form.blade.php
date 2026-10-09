@php
    $periode = $periode ?? new \App\Domain\Personnel\Models\PeriodePaie();
    $action = $periode->exists ? route('rh.periodes-paie.update', $periode) : route('rh.periodes-paie.store');
    $method = $periode->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-calendar-week"></i>
        <span>{{ $periode->exists ? 'Modifier' : 'Ouvrir une période' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.input name="libelle" label="Libellé" :required="true" :value="$periode->libelle" col="col-12"/>
            <x-form.select name="type" label="Type" :required="true" :value="$periode->type" col="col-12"
                :options="['hebdomadaire'=>'Hebdomadaire','mensuelle'=>'Mensuelle']"/>
            <x-form.input name="date_debut" label="Début" type="date" :required="true" :value="$periode->date_debut?->format('Y-m-d')" col="col-md-6"/>
            <x-form.input name="date_fin" label="Fin" type="date" :required="true" :value="$periode->date_fin?->format('Y-m-d')" col="col-md-6"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Créer</button>
    </div>
</form>