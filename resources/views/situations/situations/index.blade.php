@extends('layouts.app')
@section('title', 'Situations')
@section('page_title', 'Situations de travaux')
@section('page_icon', 'fa-file-invoice-dollar')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Situations</li>
    <li class="active">Situations</li>
@endsection
@section('page_actions')
    <x-btn href="{{ route('situations.situations.create') }}" variant="accent" icon="fa-plus" size="sm">Nouvelle situation</x-btn>
@endsection

@section('contenu')
@include('layouts.partials._chantier-filter')
<x-card padding="compact" class="mt-3">
    <x-table :headers="['N°','Chantier','Période','Montant HT','Net à payer','Statut','Actions']" :rows="$situations">
        @foreach($situations as $s)
            <tr>
                <td class="fw-700 text-btp-primary">#{{ $s->numero }}</td>
                <td>{{ $s->projet->code }}</td>
                <td>{{ $s->periode_debut->format('d/m/Y') }} → {{ $s->periode_fin->format('d/m/Y') }}</td>
                <td>{{ number_format($s->montant_periode_ht, 0, ',', ' ') }} FCFA</td>
                <td class="fw-600 text-btp-accent">{{ number_format($s->net_a_payer, 0, ',', ' ') }} FCFA</td>
                <td><x-badge :variant="match($s->statut){'approuvee'=>'success','validee'=>'info','transmise'=>'warning','facturee'=>'accent','rejetee'=>'danger',default=>'default'}">{{ ucfirst($s->statut) }}</x-badge></td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('situations.situations.show', $s) }}" class="btn-icon-sm"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('situations.situations.pdf', $s) }}" class="btn-icon-sm text-btp-danger"><i class="fas fa-file-pdf"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table>
    <div class="mt-3">{{ $situations->links() }}</div>
</x-card>
@endsection