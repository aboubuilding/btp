@extends('layouts.app')
@section('title', 'Attachements')
@section('page_title', 'Attachements')
@section('page_icon', 'fa-ruler-combined')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Situations</li>
    <li class="active">Attachements</li>
@endsection
@section('page_actions')
    <x-btn href="{{ route('situations.attachements.create') }}" variant="accent" icon="fa-plus" size="sm">Nouvel attachement</x-btn>
@endsection

@section('contenu')
@include('layouts.partials._chantier-filter')
<x-card title="Attachements" icon="fa-ruler-combined" class="mt-3">
    <x-table :headers="['N°','Chantier','Période','Établi par','Statut','Actions']" :rows="$attachements">
        @foreach($attachements as $a)
            <tr>
                <td class="fw-700 text-btp-primary">#{{ $a->numero }}</td>
                <td>{{ $a->projet->code }} — {{ $a->projet->nom }}</td>
                <td>{{ $a->periode_debut->format('d/m/Y') }} → {{ $a->periode_fin->format('d/m/Y') }}</td>
                <td>{{ $a->etabliPar?->nom_complet ?? '—' }}</td>
                <td><x-badge :variant="match($a->statut){'valide'=>'success','contradictoire'=>'warning',default=>'info'}">{{ ucfirst($a->statut) }}</x-badge></td>
                <td><a href="{{ route('situations.attachements.show', $a) }}" class="btn-icon-sm"><i class="fas fa-eye"></i></a></td>
            </tr>
        @endforeach
    </x-table>
    <div class="mt-3">{{ $attachements->links() }}</div>
</x-card>
@endsection