@php $incident = $incident ?? new \App\Domain\QHSE\Models\IncidentSecurite(); @endphp
<div class="row g-3">
    <x-form.select name="projet_id" label="Chantier" :required="true" :value="$incident->projet_id" col="col-md-6"
        :options="\App\Domain\Execution\Models\Projet::where('etat',1)->pluck('code','id')->toArray()"/>
    <x-form.input name="date_incident" label="Date et heure" type="datetime-local" :required="true"
        :value="$incident->date_incident?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')" col="col-md-6"/>
    <x-form.select name="type" label="Type" :required="true" :value="$incident->type" col="col-md-6"
        :options="['accident_travail'=>'Accident du travail','presque_accident'=>'Presque-accident','incident_materiel'=>'Incident matériel','environnement'=>'Environnement']"/>
    <x-form.select name="gravite" label="Gravité" :required="true" :value="$incident->gravite" col="col-md-6"
        :options="['mineure'=>'Mineure','moyenne'=>'Moyenne','grave'=>'Grave','mortelle'=>'Mortelle']"/>
    <x-form.textarea name="description" label="Description" :required="true" :value="$incident->description" :rows="4" col="col-12"/>
    <x-form.input name="nombre_victimes" label="Nombre de victimes" type="number" :value="$incident->nombre_victimes ?? 0" col="col-md-6"/>
    <x-form.input name="jours_arret" label="Jours d'arrêt" type="number" :value="$incident->jours_arret ?? 0" col="col-md-6"/>
    <x-form.textarea name="causes" label="Causes" :value="$incident->causes" :rows="3" col="col-12"/>
    <x-form.textarea name="actions_correctives" label="Actions correctives" :value="$incident->actions_correctives" :rows="3" col="col-12"/>
</div>