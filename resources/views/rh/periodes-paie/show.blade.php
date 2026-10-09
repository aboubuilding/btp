@extends('layouts.app')
@section('title', $periode->libelle)
@section('page_title', $periode->libelle)
@section('page_icon', 'fa-calendar-week')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('rh.periodes-paie.index') }}">Périodes</a></li>
    <li class="active">{{ $periode->libelle }}</li>
@endsection
@section('page_actions')
    @if($periode->statut === 'ouverte')
        <x-btn variant="accent" size="sm" icon="fa-play" id="btn-generer">Générer les bulletins</x-btn>
        <x-btn variant="warning" size="sm" icon="fa-lock" id="btn-cloturer">Clôturer</x-btn>
    @endif
@endsection

@section('contenu')
<div class="row g-3 mb-3">
    <div class="col-lg-4"><x-stat-card label="Total brut" :value="number_format($periode->total_brut, 0, ',', ' ').' FCFA'" icon="fa-coins" color="primary"/></div>
    <div class="col-lg-4"><x-stat-card label="Total net à payer" :value="number_format($periode->total_net, 0, ',', ' ').' FCFA'" icon="fa-hand-holding-dollar" color="success"/></div>
    <div class="col-lg-4"><x-stat-card label="Bulletins" :value="$periode->bulletins->count()" icon="fa-file-invoice-dollar" color="accent"/></div>
</div>

<x-card title="Bulletins de la période" icon="fa-file-invoice-dollar">
    <x-table :headers="['Employé','Heures','Brut','Cotisations','Impôt','Net à payer','Actions']" :rows="$periode->bulletins">
        @foreach($periode->bulletins as $b)
            <tr>
                <td class="fw-600">{{ $b->employe?->nom_complet }}</td>
                <td>{{ $b->heures_travaillees }}</td>
                <td>{{ number_format($b->brut, 0, ',', ' ') }}</td>
                <td>{{ number_format($b->cotisations_salariales, 0, ',', ' ') }}</td>
                <td>{{ number_format($b->impot_revenu, 0, ',', ' ') }}</td>
                <td class="fw-700 text-btp-accent">{{ number_format($b->net_a_payer, 0, ',', ' ') }}</td>
                <td><a href="{{ route('rh.bulletins.pdf', $b) }}" class="btn-icon-sm text-btp-danger"><i class="fas fa-file-pdf"></i></a></td>
            </tr>
        @endforeach
    </x-table>
</x-card>
@endsection

@push('js')
<script>
$(function(){
    $('#btn-generer').on('click', function(){
        Swal.fire({icon:'question', title:'Générer les bulletins ?', showCancelButton:true, confirmButtonColor:'#f0900c', confirmButtonText:'Générer', cancelButtonText:'Annuler'})
        .then(function(r){
            if(!r.isConfirmed) return;
            $.post('{{ route('rh.periodes-paie.generer', $periode) }}', {_token:'{{ csrf_token() }}'})
            .done(function(){ toastr.success('Bulletins générés.'); setTimeout(function(){location.reload();}, 700); })
            .fail(function(){ toastr.error('Erreur.'); });
        });
    });
    $('#btn-cloturer').on('click', function(){
        Swal.fire({icon:'warning', title:'Clôturer la période ?', showCancelButton:true, confirmButtonColor:'#b7950b', confirmButtonText:'Clôturer', cancelButtonText:'Annuler'})
        .then(function(r){
            if(!r.isConfirmed) return;
            $.post('{{ route('rh.periodes-paie.cloturer', $periode) }}', {_token:'{{ csrf_token() }}'})
            .done(function(){ toastr.success('Période clôturée.'); setTimeout(function(){location.reload();}, 700); })
            .fail(function(){ toastr.error('Erreur.'); });
        });
    });
});
</script>
@endpush