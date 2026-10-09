@extends('layouts.app')
@section('title', 'Catégories')
@section('page_title', 'Catégories de matériaux')
@section('page_icon', 'fa-tags')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Logistique</li>
    <li class="active">Catégories</li>
@endsection
@section('page_actions')
    <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-cat-create"
            data-modal-url="{{ route('logistique.categories.create') }}">
        <i class="fas fa-plus"></i><span>Nouvelle catégorie</span>
    </button>
@endsection

@section('contenu')
<x-table :headers="['#','Nom','Nb matériaux','']" :rows="$categories">
    @foreach($categories as $cat)
        <tr>
            <td>{{ $cat->id }}</td>
            <td class="fw-600">{{ $cat->nom }}</td>
            <td><x-badge variant="accent">{{ $cat->materiaux_count }}</x-badge></td>
            <td>
                <button class="btn-icon-sm" data-modal-open="modal-cat-edit-{{ $cat->id }}"
                        data-modal-url="{{ route('logistique.categories.edit', $cat) }}"><i class="fas fa-pen"></i></button>
            </td>
        </tr>
    @endforeach
</x-table>
@include('components.modal', ['id' => 'modal-cat-create', 'size' => 'sm'])
@foreach($categories as $cat) @include('components.modal', ['id' => "modal-cat-edit-{$cat->id}", 'size' => 'sm']) @endforeach
@endsection