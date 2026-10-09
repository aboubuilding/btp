@extends('layouts.pdf', [
    'titre' => 'SITUATION DE TRAVAUX N° ' . $situation->numero,
    'ref' => $situation->projet->code,
    'mention' => 'Situation n° ' . $situation->numero . ' — Période du ' . $situation->periode_debut->format('d/m/Y') . ' au ' . $situation->periode_fin->format('d/m/Y'),
])

@section('contenu')
<div class="info-grid">
    <div class="info-col">
        <div class="info-box">
            <div class="label">Maître d'ouvrage</div>
            <div class="value">{{ $situation->projet->client->nom }}</div>
            <div style="font-size:8pt;color:#4a5568;margin-top:4px">NIF : {{ $situation->projet->client->nif ?? '—' }}</div>
        </div>
    </div>
    <div class="info-col">
        <div class="info-box">
            <div class="label">Chantier</div>
            <div class="value">{{ $situation->projet->nom }}</div>
            <div style="font-size:8pt;color:#4a5568;margin-top:4px">Code : {{ $situation->projet->code }}</div>
        </div>
    </div>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>N° prix</th><th>Désignation</th><th>U</th>
            <th class="text-right">Qté marché</th><th class="text-right">Qté cumul</th>
            <th class="text-right">P.U.</th><th class="text-right">Montant cumul</th>
        </tr>
    </thead>
    <tbody>
        @foreach($situation->attachement->lignes as $ligne)
            @php
                $ld = $ligne->ligneDevis;
                $montantCumul = $ligne->quantite_cumulee * $ld->prix_unitaire;
            @endphp
            <tr>
                <td>{{ $ld->numero_prix ?? '—' }}</td>
                <td>{{ $ld->designation }}</td>
                <td class="text-center">{{ $ld->unite }}</td>
                <td class="text-right">{{ number_format($ld->quantite, 2, ',', ' ') }}</td>
                <td class="text-right"><strong>{{ number_format($ligne->quantite_cumulee, 2, ',', ' ') }}</strong></td>
                <td class="text-right">{{ number_format($ld->prix_unitaire, 0, ',', ' ') }}</td>
                <td class="text-right"><strong>{{ number_format($montantCumul, 0, ',', ' ') }}</strong></td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="totals-box clearfix">
    <table>
        <tr><td class="label-cell">Cumul travaux</td><td class="amount">{{ number_format($situation->montant_cumule_ht, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td class="label-cell">Situations précédentes</td><td class="amount">- {{ number_format($situation->montant_precedent_ht, 0, ',', ' ') }} FCFA</td></tr>
        <tr class="subtotal"><td class="label-cell">Travaux de la période</td><td class="amount">{{ number_format($situation->montant_periode_ht, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td class="label-cell">TVA</td><td class="amount">{{ number_format($situation->tva, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td class="label-cell">Remb. avance</td><td class="amount">- {{ number_format($situation->remboursement_avance, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td class="label-cell">Retenue garantie</td><td class="amount">- {{ number_format($situation->retenue_garantie, 0, ',', ' ') }} FCFA</td></tr>
        <tr class="total"><td class="label-cell" style="color:#fff">NET À PAYER</td><td class="amount">{{ number_format($situation->net_a_payer, 0, ',', ' ') }} FCFA</td></tr>
    </table>
</div>
<div style="clear:both"></div>

<div class="signatures">
    <div class="signature-box"><div class="sig-line">Le Maître d'œuvre</div></div>
    <div class="signature-box"><div class="sig-line">L'Entreprise</div></div>
</div>
@endsection