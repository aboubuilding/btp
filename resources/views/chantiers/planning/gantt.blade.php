@extends('layouts.app')
@section('title', 'Planning Gantt')
@section('page_title', 'Planning — ' . $projet->nom)
@section('page_icon', 'fa-chart-gantt')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('projets.index') }}">Chantiers</a></li>
    <li><a href="{{ route('projets.show', $projet) }}">{{ $projet->code }}</a></li>
    <li class="active">Planning</li>
@endsection
@section('page_actions')
    <select id="gantt-view-mode" class="form-control-btp" style="width:130px">
        <option value="Day">Jour</option><option value="Week" selected>Semaine</option><option value="Month">Mois</option>
    </select>
    <button class="btn-btp btn-outline btn-sm" id="btn-refresh-gantt"><i class="fas fa-sync"></i> Actualiser</button>
@endsection

@section('contenu')
<div style="display:flex;gap:20px;font-size:.8rem;margin-bottom:12px">
    <span style="display:inline-flex;align-items:center;gap:6px"><span style="width:12px;height:12px;border-radius:50%;background:#94a3b8"></span> À faire</span>
    <span style="display:inline-flex;align-items:center;gap:6px"><span style="width:12px;height:12px;border-radius:50%;background:#f0900c"></span> En cours</span>
    <span style="display:inline-flex;align-items:center;gap:6px"><span style="width:12px;height:12px;border-radius:50%;background:#2d8f5e"></span> Terminé</span>
    <span style="display:inline-flex;align-items:center;gap:6px"><span style="width:12px;height:12px;border-radius:50%;background:#e63946"></span> En retard</span>
</div>
<x-card padding="compact">
    <div id="gantt-wrapper">
        <div id="gantt-loader" class="text-center py-5">
            <div class="spinner-border text-btp-accent"></div>
            <p class="mt-3 text-btp-muted">Chargement du planning…</p>
        </div>
        <svg id="gantt-container" style="display:none"></svg>
    </div>
</x-card>
@endsection

@push('css')
<style>
#gantt-container{width:100%;overflow-x:auto}
#gantt-container .grid-background{fill:#f8f9fc}
#gantt-container .grid-header{fill:#1c2530}
#gantt-container .grid-row{fill:#fff}
#gantt-container .grid-row:nth-child(even){fill:#f8f9fc}
#gantt-container .bar{fill:#f0900c;rx:4;ry:4}
#gantt-container .bar-progress{fill:#c96f00}
#gantt-container .bar-label{fill:#1c2530;font-weight:600;font-size:.75rem}
#gantt-container .bar-wrapper.task-a-faire .bar{fill:#94a3b8}
#gantt-container .bar-wrapper.task-termine .bar{fill:#2d8f5e}
#gantt-container .bar-wrapper.task-retard .bar{fill:#e63946}
</style>
@endpush

@push('js')
<script>
$(function(){
    var gantt = null;
    var dataUrl = "{{ route('projets.planning.data', $projet) }}";
    var updateUrlTemplate = "{{ route('projets.taches.planning.update', ['tache' => ':id']) }}";

    function loadGantt(mode){
        $('#gantt-loader').show(); $('#gantt-container').hide().empty();
        $.ajax({ url: dataUrl, method:'GET', dataType:'json' })
        .done(function(tasks){
            $('#gantt-loader').hide();
            if(!tasks.length){
                $('#gantt-wrapper').html('<div class="btp-empty-state"><div class="empty-icon-wrap"><i class="fas fa-chart-gantt"></i></div><h4 class="empty-title">Aucune tâche planifiée</h4></div>');
                return;
            }
            $('#gantt-container').show();
            var svg = document.getElementById('gantt-container');
            gantt = new Gantt(svg, tasks, {
                view_mode: mode || 'Week', language:'fr', readonly:false, bar_height:24, padding:18, column_width:45,
                date_format:'YYYY-MM-DD',
                on_date_change: function(task, start, end){ updateTask(task, start, end, task.progress); },
                on_progress_change: function(task, progress){ updateTask(task, task._start, task._end, progress); }
            });
        })
        .fail(function(){ $('#gantt-loader').html('<div class="alert alert-danger">Erreur de chargement.</div>'); });
    }

    function updateTask(task, start, end, progress){
        var id = task.id.replace('tache-', '');
        $.ajax({
            url: updateUrlTemplate.replace(':id', id),
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), _method:'PUT',
                start: moment(start).format('YYYY-MM-DD'), end: moment(end).format('YYYY-MM-DD'), progress: Math.round(progress) },
            dataType: 'json'
        }).done(function(){ toastr.success('Tâche mise à jour.'); })
          .fail(function(){ toastr.error('Erreur.'); loadGantt($('#gantt-view-mode').val()); });
    }

    $('#gantt-view-mode').on('change', function(){ loadGantt($(this).val()); });
    $('#btn-refresh-gantt').on('click', function(){ loadGantt($('#gantt-view-mode').val()); toastr.info('Planning rechargé.'); });

    loadGantt('Week');
});
</script>
@endpush