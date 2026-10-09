@php
    $releve = $releve ?? new \App\Domain\ParcMateriel\Models\ReleveCarburant();
    $action = $releve->exists ? route('materiel.carburant.update', $releve) : route('materiel.carburant.store', $equipement);
    $method = $releve->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-gas-pump"></i> Nouveau relevé carburant</h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.input name="date_releve" label="Date" type="date" :required="true"
                :value="$releve->date_releve?->format('Y-m-d') ?? now()->format('Y-m-d')" col="col-md-6"/>
            <x-form.input name="compteur" label="Compteur" type="number" step="0.01" :required="true"
                :value="$releve->compteur ?? $equipement->compteur_heures_actuel" col="col-md-6"/>
            <x-form.input name="quantite" label="Quantité (L)" type="number" step="0.01" :required="true" :value="$releve->quantite" col="col-md-4"/>
            <x-form.input name="prix_unitaire" label="P.U. (FCFA/L)" type="number" step="0.01" :required="true" :value="$releve->prix_unitaire" col="col-md-4"/>
            <x-form.input name="cout_total" label="Coût total" type="number" step="0.01" :value="$releve->cout_total" col="col-md-4"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>