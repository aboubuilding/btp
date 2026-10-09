@extends('layouts.app')
@section('title', 'Pointage')
@section('page_title', 'Pointage du ' . now()->format('d/m/Y'))
@section('page_icon', 'fa-clock')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('rh.pointages.index') }}">Pointages</a></li>
    <li class="active">Saisie</li>
@endsection

@section('contenu')
<form method="POST" action="{{ route('rh.pointages.store') }}">
    @csrf
    <input type="hidden" name="projet_id" value="{{ $projet->id }}">
    <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">

    <x-card :title="'Pointage — ' . $projet->nom . ' (' . $projet->code . ')'" icon="fa-clipboard-user">
        <x-slot:actions>
            <button type="button" class="btn-btp btn-outline btn-sm" id="btn-all-present">
                <i class="fas fa-check-double"></i> Tous présents
            </button>
        </x-slot:actions>
        <div class="table-responsive">
            <table class="table-btp">
                <thead><tr><th>Employé</th><th>Poste</th><th>Statut</th><th>Arrivée</th><th>Départ</th><th>Heures</th></tr></thead>
                <tbody>
                    @foreach($equipe as $i => $m)
                        <tr>
                            <td><div class="fw-600">{{ $m->employe->nom_complet }}</div><small class="text-btp-muted">{{ $m->employe->matricule }}</small></td>
                            <td>{{ $m->employe->poste?->nom ?? '—' }}</td>
                            <td>
                                <select name="lignes[{{ $i }}][statut]" class="form-control-btp statut-select" style="padding:6px 10px;font-size:.83rem">
                                    <option value="present">Présent</option>
                                    <option value="absent">Absent</option>
                                    <option value="retard">Retard</option>
                                    <option value="conge">Congé</option>
                                    <option value="maladie">Maladie</option>
                                    <option value="ferie">Férié</option>
                                </select>
                            </td>
                            <td><input type="time" name="lignes[{{ $i }}][heure_arrivee]" value="07:00" class="form-control-btp input-time" data-i="{{ $i }}" style="padding:6px 10px;font-size:.83rem"></td>
                            <td><input type="time" name="lignes[{{ $i }}][heure_depart]" value="17:00" class="form-control-btp input-time" data-i="{{ $i }}" style="padding:6px 10px;font-size:.83rem"></td>
                            <td><span class="fw-700 text-btp-accent hours-display" data-i="{{ $i }}">10.00 h</span></td>
                            <input type="hidden" name="lignes[{{ $i }}][employee_id]" value="{{ $m->employe->id }}">
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-slot:footer>
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-btp-muted small"><i class="fas fa-info-circle"></i> Seuls les pointages validés comptent pour la paie (RG-P02)</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('rh.pointages.index') }}" class="btn-btp btn-ghost"><i class="fas fa-times"></i> Annuler</a>
                    <button type="submit" class="btn-btp btn-accent"><i class="fas fa-save"></i> Enregistrer</button>
                </div>
            </div>
        </x-slot:footer>
    </x-card>
</form>
@endsection

@push('js')
<script>
$(function(){
    $('.input-time').on('change', function(){
        var i = $(this).data('i');
        var a = $('[name="lignes['+i+'][heure_arrivee]"]').val();
        var d = $('[name="lignes['+i+'][heure_depart]"]').val();
        if(a && d){
            var t1 = a.split(':').map(Number), t2 = d.split(':').map(Number);
            var h = ((t2[0]*60+t2[1]) - (t1[0]*60+t1[1])) / 60;
            $('.hours-display[data-i="'+i+'"]').text(h.toFixed(2) + ' h');
        }
    });
    $('#btn-all-present').on('click', function(){ $('.statut-select').val('present'); });
});
</script>
@endpush