@php
    $caisse = $caisse ?? new \App\Domain\Finances\Models\Caisse();
    $action = $caisse->exists ? route('finances.caisses.update', $caisse) : route('finances.caisses.store');
    $method = $caisse->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-cash-register"></i>
        <span>{{ $caisse->exists ? 'Modifier' : 'Nouvelle caisse' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.input name="libelle" label="Libellé" :required="true" :value="$caisse->libelle" col="col-12"/>
            <x-form.select name="projet_id" label="Chantier" :value="$caisse->projet_id" col="col-12"
                :options="\App\Domain\Execution\Models\Projet::where('etat',1)->pluck('code','id')->toArray()" placeholder="— Siège social —"/>
            <x-form.input name="solde_initial" label="Solde initial (FCFA)" type="number" step="0.01" :value="$caisse->solde_initial ?? 0" col="col-12"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>