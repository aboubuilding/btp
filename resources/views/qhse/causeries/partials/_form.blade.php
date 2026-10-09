@php
    $causerie = $causerie ?? new \App\Domain\QHSE\Models\CauserieSecurite();
    $action = $causerie->exists ? route('qhse.causeries.update', $causerie) : route('qhse.causeries.store');
    $method = $causerie->exists ? 'PUT' : 'POST';
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-comments"></i>
        <span>{{ $causerie->exists ? 'Modifier' : 'Nouvelle causerie' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ $action }}" method="POST">
    @csrf @if($method === 'PUT') @method('PUT') @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <x-form.select name="projet_id" label="Chantier" :required="true" :value="$causerie->projet_id" col="col-12"
                :options="\App\Domain\Execution\Models\Projet::where('etat',1)->pluck('code','id')->toArray()"/>
            <x-form.input name="date" label="Date" type="date" :required="true" :value="$causerie->date?->format('Y-m-d') ?? now()->format('Y-m-d')" col="col-md-6"/>
            <x-form.input name="nombre_participants" label="Participants" type="number" :required="true" :value="$causerie->nombre_participants ?? 0" col="col-md-6"/>
            <x-form.input name="theme" label="Thème" :required="true" :value="$causerie->theme" col="col-12"/>
            <x-form.select name="anime_par" label="Animée par" :value="$causerie->anime_par" col="col-12"
                :options="\App\Domain\Personnel\Models\Employe::where('etat',1)->get()->pluck('nom_complet','id')->toArray()"/>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-check"></i> Enregistrer</button>
    </div>
</form>