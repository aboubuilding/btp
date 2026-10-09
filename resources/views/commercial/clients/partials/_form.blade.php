@php $client = $client ?? new \App\Domain\Commercial\Models\Client(); @endphp
<div class="row g-3">
    <x-form.input name="nom" label="Raison sociale / Nom" icon="fa-building" :required="true" :value="$client->nom" col="col-md-8"/>
    <x-form.select name="type" label="Type" :required="true" :value="$client->type" col="col-md-4"
        :options="['particulier'=>'Particulier','entreprise'=>'Entreprise','public'=>'Organisme public']"/>
    <x-form.input name="contact" label="Personne de contact" icon="fa-user" :value="$client->contact" col="col-md-6"/>
    <x-form.input name="telephone" label="Téléphone" icon="fa-phone" :value="$client->telephone" col="col-md-6"/>
    <x-form.input name="email" label="Email" type="email" icon="fa-envelope" :value="$client->email" col="col-md-6"/>
    <x-form.input name="nif" label="NIF" icon="fa-id-badge" :value="$client->nif" col="col-md-6"/>
    <x-form.input name="adresse" label="Adresse" icon="fa-location-dot" :value="$client->adresse" col="col-12"/>
</div>