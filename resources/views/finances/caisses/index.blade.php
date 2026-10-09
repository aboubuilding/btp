@extends('layouts.app')
@section('title', 'Caisses')
@section('page_title', 'Caisses')
@section('page_icon', 'fa-cash-register')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Finances</li>
    <li class="active">Caisses</li>
@endsection
@section('page_actions')
    <button type="button" class="btn-btp btn-accent btn-sm" data-modal-open="modal-caisse-create"
            data-modal-url="{{ route('finances.caisses.create') }}">
        <i class="fas fa-plus"></i><span>Nouvelle caisse</span>
    </button>
@endsection

@section('contenu')
<div class="row g-3">
    @foreach($caisses as $c)
        <div class="col-lg-4 col-md-6">
            <div class="card-btp p-3">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <small class="text-btp-muted text-uppercase fw-700">Caisse</small>
                        <h5 class="mt-1">{{ $c->libelle }}</h5>
                        <small class="text-btp-muted">{{ $c->projet?->code ?? 'Siège social' }}</small>
                    </div>
                    <button class="btn-icon-sm" data-modal-open="modal-caisse-edit-{{ $c->id }}"
                            data-modal-url="{{ route('finances.caisses.edit', $c) }}"><i class="fas fa-pen"></i></button>
                </div>
                <div class="fw-800 text-btp-accent" style="font-size:1.6rem">{{ number_format($c->solde_actuel, 0, ',', ' ') }} FCFA</div>
                <small class="text-btp-muted">Solde initial : {{ number_format($c->solde_initial, 0, ',', ' ') }}</small>
            </div>
        </div>
    @endforeach
</div>
@include('components.modal', ['id' => 'modal-caisse-create', 'size' => 'sm'])
@foreach($caisses as $c) @include('components.modal', ['id' => "modal-caisse-edit-{$c->id}", 'size' => 'sm']) @endforeach
@endsection