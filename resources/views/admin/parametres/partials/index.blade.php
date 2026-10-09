@extends('layouts.app')
@section('title', 'Paramètres')
@section('page_title', 'Paramètres généraux')
@section('page_icon', 'fa-sliders-h')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Admin</li>
    <li class="active">Paramètres</li>
@endsection
@section('page_actions')
    <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-param-create"
            data-modal-url="{{ route('admin.parametres.create') }}">
        <i class="fas fa-plus"></i><span>Nouveau paramètre</span>
    </button>
@endsection

@section('contenu')
@foreach($parametres as $groupe => $items)
    <x-card :title="ucfirst($groupe)" icon="fa-cog" class="mb-3">
        <x-table :headers="['Clé','Valeur','Type','Description','']" :rows="$items">
            @foreach($items as $p)
                <tr>
                    <td><code class="fw-700">{{ $p->cle }}</code></td>
                    <td class="fw-600 text-btp-accent">{{ $p->valeur }}</td>
                    <td><x-badge variant="default">{{ $p->type }}</x-badge></td>
                    <td class="text-btp-muted small">{{ $p->description }}</td>
                    <td><button class="btn-icon-sm" data-modal-open="modal-param-edit-{{ $p->id }}"
                                data-modal-url="{{ route('admin.parametres.edit', $p) }}"><i class="fas fa-pen"></i></button></td>
                </tr>
            @endforeach
        </x-table>
    </x-card>
@endforeach

@include('components.modal', ['id' => 'modal-param-create', 'size' => 'sm'])
@foreach($parametres as $items) @foreach($items as $p) @include('components.modal', ['id' => "modal-param-edit-{$p->id}", 'size' => 'sm']) @endforeach @endforeach
@endsection