@php $employe = $employe ?? new \App\Domain\Personnel\Models\Employe(); @endphp
<div class="row g-3">
    <x-form.input name="matricule" label="Matricule" icon="fa-hashtag" :required="true" :value="$employe->matricule ?? 'EMP-'.str_pad(rand(1,999),4,'0',STR_PAD_LEFT)" col="col-md-4"/>
    <x-form.input name="nom" label="Nom" :required="true" :value="$employe->nom" col="col-md-4"/>
    <x-form.input name="prenom" label="Prénom" :required="true" :value="$employe->prenom" col="col-md-4"/>
    <x-form.input name="date_naissance" label="Date de naissance" type="date" :value="$employe->date_naissance?->format('Y-m-d')" col="col-md-6"/>
    <x-form.input name="numero_cnss" label="N° CNSS" :value="$employe->numero_cnss" col="col-md-6"/>
    <x-form.select name="departement_id" label="Département" :value="$employe->departement_id" col="col-md-6"
        :options="\App\Domain\Personnel\Models\Departement::where('etat',1)->pluck('nom','id')->toArray()"/>
    <x-form.select name="poste_id" label="Poste" :value="$employe->poste_id" col="col-md-6"
        :options="\App\Domain\Personnel\Models\Poste::where('etat',1)->pluck('nom','id')->toArray()"/>
    <x-form.select name="type_contrat" label="Type de contrat" :required="true" :value="$employe->type_contrat ?? 'cdi'" col="col-md-4"
        :options="['cdi'=>'CDI','cdd'=>'CDD','journalier'=>'Journalier','stage'=>'Stage','prestataire'=>'Prestataire']"/>
    <x-form.input name="date_embauche" label="Date d'embauche" type="date" :value="$employe->date_embauche?->format('Y-m-d')" col="col-md-4"/>
    <x-form.input name="salaire_base" label="Salaire de base (FCFA)" type="number" step="0.01" :required="true" :value="$employe->salaire_base ?? 0" col="col-md-4"/>
    <x-form.input name="telephone" label="Téléphone" icon="fa-phone" :value="$employe->telephone" col="col-md-6"/>
    <x-form.input name="contact_urgence" label="Contact d'urgence" icon="fa-phone-flip" :value="$employe->contact_urgence" col="col-md-6"/>
    <x-form.select name="mode_paiement" label="Mode de paiement" :value="$employe->mode_paiement ?? 'especes'" col="col-md-6"
        :options="['especes'=>'Espèces','cheque'=>'Chèque','virement'=>'Virement','mobile_money'=>'Mobile Money']"/>
    <x-form.select name="statut" label="Statut" :value="$employe->statut ?? 'actif'" col="col-md-6"
        :options="['actif'=>'Actif','inactif'=>'Inactif','suspendu'=>'Suspendu']"/>
</div>