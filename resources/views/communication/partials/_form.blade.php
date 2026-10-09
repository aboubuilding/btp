@php
    $communication = $communication ?? new \App\Domain\Socle\Models\Communication();
    $communicable  = $communicable ?? null;
@endphp
<div class="modal-header-btp">
    <h5 class="modal-title"><i class="fas fa-envelope"></i>
        <span>{{ $communication->exists ? 'Modifier' : 'Nouvelle communication' }}</span></h5>
    <button type="button" class="btn-close-btp" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
</div>
<form data-ajax="true" action="{{ route('communications.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($communicable)
        <input type="hidden" name="communicable_type" value="{{ get_class($communicable) }}">
        <input type="hidden" name="communicable_id" value="{{ $communicable->id }}">
    @endif
    <div class="modal-body-btp">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label-btp">Destinataires <span class="text-btp-danger">*</span></label>
                <div id="destinataires-list">
                    <div class="destinataire-row d-flex gap-2 mb-2">
                        <input type="email" name="destinataires[0][email]" class="form-control-btp" placeholder="email@exemple.com" required>
                        <input type="text" name="destinataires[0][nom]" class="form-control-btp" placeholder="Nom">
                        <button type="button" class="btn-icon-sm text-btp-danger" data-remove-row><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <button type="button" class="btn-btp btn-outline btn-sm mt-2" id="btn-add-destinataire">
                    <i class="fas fa-plus"></i> Ajouter un destinataire
                </button>
            </div>
            <x-form.input name="sujet" label="Sujet" icon="fa-tag" :required="true" col="col-12"/>
            <x-form.textarea name="corps" label="Corps du message" :required="true" :rows="8" col="col-12"/>
            <x-form.file name="pieces_jointes[]" label="Pièces jointes" help="Taille max : 10 Mo" col="col-12"/>
            <x-form.input name="planifie_le" label="Planifier (optionnel)" type="datetime-local" col="col-md-6"/>
            <div class="col-md-6 d-flex align-items-end">
                <label><input type="checkbox" name="envoyer_maintenant" value="1" checked> Envoyer immédiatement</label>
            </div>
        </div>
    </div>
    <div class="modal-footer-btp">
        <button type="button" class="btn-btp btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Annuler</button>
        <button type="submit" class="btn-btp btn-accent"><i class="fas fa-paper-plane"></i> Enregistrer</button>
    </div>
</form>
<script>
$(function(){
    var index = 1;
    $('#btn-add-destinataire').on('click', function(){
        $('#destinataires-list').append('<div class="destinataire-row d-flex gap-2 mb-2"><input type="email" name="destinataires['+index+'][email]" class="form-control-btp" placeholder="email@exemple.com" required><input type="text" name="destinataires['+index+'][nom]" class="form-control-btp" placeholder="Nom"><button type="button" class="btn-icon-sm text-btp-danger" data-remove-row><i class="fas fa-trash"></i></button></div>');
        index++;
    });
    $(document).on('click', '[data-remove-row]', function(){
        if ($('#destinataires-list .destinataire-row').length > 1) $(this).closest('.destinataire-row').remove();
        else toastr.warning('Au moins un destinataire requis.');
    });
});
</script>