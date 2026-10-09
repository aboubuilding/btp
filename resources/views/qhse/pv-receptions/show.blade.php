@extends('layouts.app')
@section('title', 'PV de réception')
@section('page_title', 'PV — ' . $pv->projet?->nom)
@section('page_icon', 'fa-file-signature')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li><a href="{{ route('qhse.pv-receptions.index') }}">PV de réception</a></li>
    <li class="active">{{ ucfirst($pv->type) }}</li>
@endsection

@section('contenu')
<x-card title="Réserves" icon="fa-exclamation-circle">
    <x-slot:actions>
        <button type="button" class="btn-btp btn-danger btn-sm" data-modal-open="modal-reserve-create"
                data-modal-url="{{ route('qhse.reserves.create', $pv) }}">
            <i class="fas fa-plus"></i><span>Ajouter une réserve</span>
        </button>
    </x-slot:actions>
    <x-table :headers="['Localisation','Description','Responsable','Échéance','Statut','']" :rows="$pv->reserves">
        @foreach($pv->reserves as $r)
            <tr>
                <td>{{ $r->localisation }}</td>
                <td>{{ Str::limit($r->description, 60) }}</td>
                <td>{{ $r->responsable_id }}</td>
                <td>{{ $r->date_limite?->format('d/m/Y') }}</td>
                <td><x-badge :variant="$r->statut === 'levee' ? 'success' : 'danger'">{{ ucfirst($r->statut) }}</x-badge></td>
                <td>
                    <button class="btn-icon-sm" data-modal-open="modal-reserve-edit-{{ $r->id }}"
                            data-modal-url="{{ route('qhse.reserves.edit', $r) }}"><i class="fas fa-pen"></i></button>
                    @if($r->statut === 'ouverte')
                        <button class="btn-icon-sm text-btp-success" data-lever-reserve="{{ $r->id }}"><i class="fas fa-check"></i></button>
                    @endif
                </td>
            </tr>
        @endforeach
    </x-table>
</x-card>

@include('components.modal', ['id' => 'modal-reserve-create'])
@foreach($pv->reserves as $r) @include('components.modal', ['id' => "modal-reserve-edit-{$r->id}"]) @endforeach
@endsection

@push('js')
<script>
$(function(){
    $(document).on('click', '[data-lever-reserve]', function(){
        var id = $(this).data('lever-reserve');
        $.post('/qhse/reserve/' + id + '/lever', {_token:'{{ csrf_token() }}'})
        .done(function(){ toastr.success('Réserve levée.'); setTimeout(function(){location.reload();}, 700); })
        .fail(function(){ toastr.error('Erreur.'); });
    });
});
</script>
@endpush