@php $employes = \App\Domain\Personnel\Models\Employe::where('etat',1)->orderBy('nom')->get(); @endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Affecter un employé</h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ route('projets.equipe.store', $projet) }}" method="POST">
    @csrf
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.select name="employee_id" label="Employé" :required="true" col="col-12"
                :options="$employes->pluck('nom_complet','id')->toArray()"/>
            <x-form.select name="role_chantier" label="Rôle" col="col-12"
                :options="['chef_equipe'=>'Chef d\'équipe','ouvrier'=>'Ouvrier','pointeur'=>'Pointeur','topographe'=>'Topographe']"/>
            <x-form.input name="date_debut" label="Début" type="date" :required="true" :value="now()->format('Y-m-d')" col="col-md-6"/>
            <x-form.input name="date_fin" label="Fin (optionnel)" type="date" col="col-md-6"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Affecter</button>
    </div>
</form>