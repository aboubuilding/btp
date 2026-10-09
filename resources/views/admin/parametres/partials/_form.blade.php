@php
    $parametre = $parametre ?? new \App\Domain\Socle\Models\Parametre();
    $action = $parametre->exists ? route('admin.parametres.update', $parametre) : route('admin.parametres.store');
    $method = $parametre->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-sliders-h"></i>
        <span>{{ $parametre->exists ? 'Modifier' : 'Nouveau paramètre' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.input name="cle" label="Clé" :required="true" :value="$parametre->cle" help="Ex : tva.taux" col="col-12"/>
            <x-form.select name="type" label="Type" :required="true" :value="$parametre->type" col="col-md-6"
                :options="['string'=>'Texte','decimal'=>'Décimal','integer'=>'Entier','boolean'=>'Booléen','json'=>'JSON']"/>
            <x-form.input name="valeur" label="Valeur" :required="true" :value="$parametre->valeur" col="col-md-6"/>
            <x-form.input name="description" label="Description" :value="$parametre->description" col="col-12"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>