@extends('layouts.app')
@section('title', 'Périodes de paie')
@section('page_title', 'Périodes de paie')
@section('page_icon', 'fa-calendar-week')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>RH</li>
    <li class="active">Périodes</li>
@endsection
@section('page_actions')
    <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-periode-create"
            data-modal-url="{{ route('rh.periodes-paie.create') }}">
        <i class="fas fa-plus"></i><span>Ouvrir une période</span>
    </button>
@endsection

@section('contenu')
<x-table :headers="['Libellé','Type','Début','Fin','Statut','Bulletins','Actions']" :rows="$periodes">
    @foreach($periodes as $p)
        <tr>
            <td class="fw-600">{{ $p->libelle }}</td>
            <td><x-badge variant="accent">{{ ucfirst($p->type) }}</x-badge></td>
            <td>{{ $p->date_debut->format('d/m/Y') }}</td>
            <td>{{ $p->date_fin->format('d/m/Y') }}</td>
            <td><x-badge :variant="match($p->statut){'ouverte'=>'info','cloturee'=>'warning','payee'=>'success'}">{{ ucfirst($p->statut) }}</x-badge></td>
            <td>{{ $p->bulletins_count }}</td>
            <td>
                <a href="{{ route('rh.periodes-paie.show', $p) }}" class="btn-icon-sm"><i class="fas fa-eye"></i></a>
                @if($p->statut === 'ouverte')
                    <button class="btn-icon-sm" data-modal-open="modal-periode-edit-{{ $p->id }}"
                            data-modal-url="{{ route('rh.periodes-paie.edit', $p) }}"><i class="fas fa-pen"></i></button>
                @endif
            </td>
        </tr>
    @endforeach
</x-table>
@include('components.modal', ['id' => 'modal-periode-create'])
@foreach($periodes as $p) @include('components.modal', ['id' => "modal-periode-edit-{$p->id}"]) @endforeach
@endsection