@extends('layouts.app')
@section('title', 'Rôles')
@section('page_title', 'Rôles & permissions')
@section('page_icon', 'fa-user-shield')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Admin</li>
    <li class="active">Rôles</li>
@endsection
@section('page_actions')
    <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-role-create"
            data-modal-url="{{ route('admin.roles.create') }}">
        <i class="fas fa-plus"></i><span>Nouveau rôle</span>
    </button>
@endsection

@section('contenu')
<div class="row g-3">
    @foreach($roles as $role)
        <div class="col-lg-4 col-md-6">
            <div class="card-btp p-3">
                <div class="d-flex justify-content-between mb-2">
                    <x-badge variant="accent">{{ $role->slug }}</x-badge>
                    <button class="btn-icon-sm" data-modal-open="modal-role-edit-{{ $role->id }}"
                            data-modal-url="{{ route('admin.roles.edit', $role) }}"><i class="fas fa-pen"></i></button>
                </div>
                <h5 class="mb-1">{{ $role->nom }}</h5>
                <small class="text-btp-muted">{{ $role->users_count ?? 0 }} utilisateur(s)</small>
            </div>
        </div>
    @endforeach
</div>
@include('components.modal', ['id' => 'modal-role-create'])
@foreach($roles as $role) @include('components.modal', ['id' => "modal-role-edit-{$role->id}"]) @endforeach
@endsection