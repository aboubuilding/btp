@extends('layouts.app')
@section('title', 'Communications')
@section('page_title', 'Communications')
@section('page_icon', 'fa-envelope')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li class="active">Communications</li>
@endsection
@section('page_actions')
    <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-communication-create"
            data-modal-url="{{ route('communications.create') }}">
        <i class="fas fa-plus"></i><span>Nouvelle communication</span>
    </button>
@endsection

@section('contenu')
<div class="row g-3 mb-3">
    <div class="col-lg-3"><x-stat-card label="Total envoyés" :value="$stats['envoyes'] ?? 0" icon="fa-paper-plane" color="success"/></div>
    <div class="col-lg-3"><x-stat-card label="Brouillons" :value="$stats['brouillons'] ?? 0" icon="fa-file-lines" color="info"/></div>
    <div class="col-lg-3"><x-stat-card label="Échecs" :value="$stats['echecs'] ?? 0" icon="fa-triangle-exclamation" color="danger"/></div>
    <div class="col-lg-3"><x-stat-card label="Ce mois" :value="$stats['mois'] ?? 0" icon="fa-calendar" color="accent"/></div>
</div>

<x-table :headers="['Date','Sujet','Destinataires','Statut','Actions']" :rows="$communications">
    @foreach($communications as $c)
        <tr>
            <td><div class="fw-600">{{ $c->created_at->format('d/m/Y') }}</div><small class="text-btp-muted">{{ $c->created_at->format('H:i') }}</small></td>
            <td><div class="fw-600">{{ $c->sujet }}</div><small class="text-btp-muted">{{ Str::limit(strip_tags($c->corps), 60) }}</small></td>
            <td><x-badge variant="accent">{{ $c->nb_destinataires }}</x-badge></td>
            <td><x-badge :variant="match($c->statut){'envoye'=>'success','echoue'=>'danger','planifie'=>'warning',default=>'info'}">{{ ucfirst($c->statut) }}</x-badge></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('communications.show', $c) }}" class="btn-icon-sm"><i class="fas fa-eye"></i></a>
                    @if(in_array($c->statut, ['brouillon','echoue']))
                        <button class="btn-icon-sm text-btp-accent" data-communication-send="{{ $c->id }}"><i class="fas fa-paper-plane"></i></button>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
</x-table>
<div class="mt-3">{{ $communications->links() }}</div>

@include('components.modal', ['id' => 'modal-communication-create', 'size' => 'lg'])
@endsection

@push('js')
<script>
$(function(){
    $(document).on('click', '[data-communication-send]', function(){
        var id = $(this).data('communication-send');
        Swal.fire({icon:'question', title:'Envoyer cet email ?', showCancelButton:true, confirmButtonColor:'#f0900c', confirmButtonText:'Envoyer', cancelButtonText:'Annuler'})
        .then(function(r){
            if(!r.isConfirmed) return;
            $.post('/communications/' + id + '/envoyer', {_token:'{{ csrf_token() }}'})
            .done(function(){ toastr.success('Email envoyé.'); setTimeout(function(){location.reload();}, 700); })
            .fail(function(){ toastr.error('Échec.'); });
        });
    });
});
</script>
@endpush