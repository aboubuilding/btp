@php $projet = $projet ?? new \App\Domain\Execution\Models\Projet(); @endphp
<div class="row g-3">
    <x-form.input name="code" label="Code" icon="fa-hashtag" :required="true" :value="$projet->code ?? 'CH-' . date('Y') . '-001'" col="col-md-4"/>
    <x-form.select name="type" label="Type" :required="true" :value="$projet->type" col="col-md-4"
        :options="['batiment'=>'Bâtiment','route'=>'Route','ouvrage_art'=>'Ouvrage d\'art','terrassement'=>'Terrassement','reseau'=>'Réseaux']"/>
    <x-form.select name="statut" label="Statut" :value="$projet->statut ?? 'planifie'" col="col-md-4"
        :options="['planifie'=>'Planifié','en_cours'=>'En cours','suspendu'=>'Suspendu','termine'=>'Terminé','annule'=>'Annulé']"/>
    <x-form.input name="nom" label="Intitulé" icon="fa-tag" :required="true" :value="$projet->nom" col="col-12"/>
    <x-form.select name="client_id" label="Client" :required="true" :value="$projet->client_id" col="col-md-6"
        :options="\App\Domain\Commercial\Models\Client::where('etat',1)->pluck('nom','id')->toArray()"/>
    <x-form.select name="marche_id" label="Marché" :value="$projet->marche_id" col="col-md-6"
        :options="\App\Domain\Commercial\Models\Marche::where('etat',1)->pluck('reference','id')->toArray()"/>
    <x-form.input name="adresse" label="Adresse" icon="fa-location-dot" :value="$projet->adresse" col="col-md-8"/>
    <x-form.input name="ville" label="Ville" icon="fa-city" :required="true" :value="$projet->ville" col="col-md-4"/>
    <x-form.select name="conducteur_travaux_id" label="Conducteur de travaux" :required="true" :value="$projet->conducteur_travaux_id" col="col-md-6"
        :options="\App\Domain\Personnel\Models\Employe::where('etat',1)->get()->pluck('nom_complet','id')->toArray()"/>
    <x-form.select name="chef_chantier_id" label="Chef de chantier" :value="$projet->chef_chantier_id" col="col-md-6"
        :options="\App\Domain\Personnel\Models\Employe::where('etat',1)->get()->pluck('nom_complet','id')->toArray()"/>
    <x-form.input name="date_debut_prevue" label="Début prévu" type="date" :required="true" :value="$projet->date_debut_prevue?->format('Y-m-d')" col="col-md-6"/>
    <x-form.input name="date_fin_prevue" label="Fin prévue" type="date" :required="true" :value="$projet->date_fin_prevue?->format('Y-m-d')" col="col-md-6"/>
    <x-form.input name="montant_contrat" label="Montant contrat (FCFA)" type="number" step="0.01" :required="true" :value="$projet->montant_contrat" col="col-md-6"/>
    <x-form.input name="budget_prevu" label="Budget prévu (FCFA)" type="number" step="0.01" :value="$projet->budget_prevu" col="col-md-6"/>
    <x-form.textarea name="description" label="Description" :value="$projet->description" :rows="4" col="col-12"/>
</div>