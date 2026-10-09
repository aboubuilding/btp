@php
    $reserve = $reserve ?? new \App\Domain\QHSE\Models\Reserve();
    $action = $reserve->exists ? route('qhse.reserves.update', $reserve) : route('qhse.reserves.store', $pv);
    $method = $reserve->exists ? 'PUT' : 'POST';
    $employes = \App\Domain\Personnel\Models\Employe::where('etat',1)->get();
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-exclamation-circle"></i>
        <span>{{ $reserve->exists ? 'Modifier' : 'Nouvelle réserve' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.input name="localisation" label="Localisation" :required="true" :value="$reserve->localisation" col="col-12"/>
            <x-form.textarea name="description" label="Description" :required="true" :value="$reserve->description" :rows="3" col="col-12"/>
            <x-form.select name="type_responsable" label="Type responsable" :required="true" :value="$reserve->type_responsable ?? 'Employe'" col="col-md-6"
                :options="['Employe'=>'Employé','Soustraitant'=>'Sous-traitant']"/>
            <x-form.input name="date_limite" label="Date limite" type="date" :value="$reserve->date_limite?->format('Y-m-d')" col="col-md-6"/>
            <x-form.select name="responsable_id" label="Responsable" :required="true" :value="$reserve->responsable_id" col="col-12"
                :options="$employes->pluck('nom_complet','id')->toArray()"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-danger"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>