@php $projets = \App\Domain\Execution\Models\Projet::where('etat', 1)->where('statut', 'en_cours')->get(); @endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-map-location-dot"></i> Nouvelle affectation</h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ route('materiel.affectations.store', $equipement) }}" method="POST">
    @csrf
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.select name="projet_id" label="Chantier" :required="true" col="col-12"
                :options="$projets->pluck('code','id')->toArray()"/>
            <x-form.input name="date_debut" label="Début" type="date" :required="true" :value="now()->format('Y-m-d')" col="col-md-6"/>
            <x-form.input name="compteur_debut" label="Compteur début" type="number" step="0.01" :value="$equipement->compteur_heures_actuel" col="col-md-6"/>
            <x-form.input name="date_fin" label="Fin (optionnel)" type="date" col="col-12"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Affecter</button>
    </div>
</form>