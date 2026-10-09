@php
    $role = $role ?? new \App\Domain\Socle\Models\Role();
    $action = $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store');
    $method = $role->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-user-shield"></i>
        <span>{{ $role->exists ? 'Modifier' : 'Nouveau rôle' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.input name="nom" label="Nom du rôle" :required="true" :value="$role->nom" col="col-12"/>
            <x-form.input name="slug" label="Identifiant technique" :required="true" :value="$role->slug" help="Ex : conducteur_travaux" col="col-12"/>
            <x-form.input name="description" label="Description" :value="$role->description" col="col-12"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>