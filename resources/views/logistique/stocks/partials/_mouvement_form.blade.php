@php
    $entrepots = \App\Domain\Approvisionnement\Models\Entrepot::where('etat', 1)->get();
    $materiaux = \App\Domain\Approvisionnement\Models\Materiau::where('etat', 1)->get();
    $projets   = \App\Domain\Execution\Models\Projet::where('etat', 1)->where('statut', 'en_cours')->get();
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-right-left"></i> Nouveau mouvement</h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ route('logistique.stocks.mouvement.store') }}" method="POST">
    @csrf
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.select name="type" label="Type" :required="true" col="col-md-6"
                :options="['entree'=>'Entrée','sortie'=>'Sortie','ajustement'=>'Ajustement']"/>
            <x-form.select name="entrepot_id" label="Dépôt" :required="true" col="col-md-6"
                :options="$entrepots->pluck('nom','id')->toArray()"/>
            <x-form.select name="materiau_id" label="Matériau" :required="true" col="col-12"
                :options="$materiaux->pluck('nom','id')->toArray()"/>
            <x-form.input name="quantite" label="Quantité" type="number" step="0.01" :required="true" col="col-md-6"/>
            <x-form.input name="prix_unitaire" label="Prix unitaire" type="number" step="0.01" :value="0" col="col-md-6"/>
            <x-form.select name="projet_id" label="Chantier (obligatoire pour sortie)" col="col-12"
                :options="$projets->pluck('code','id')->toArray()" placeholder="— Aucun —"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>