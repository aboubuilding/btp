@php
    $categorie = $categorie ?? new \App\Domain\Approvisionnement\Models\CategorieMateriau();
    $action = $categorie->exists ? route('logistique.categories.update', $categorie) : route('logistique.categories.store');
    $method = $categorie->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-tag"></i>
        <span>{{ $categorie->exists ? 'Modifier' : 'Nouvelle catégorie' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <x-form.input name="nom" label="Nom de la catégorie" :required="true" :value="$categorie->nom" col="col-12"/>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>