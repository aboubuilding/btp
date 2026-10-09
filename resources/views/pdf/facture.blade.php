@extends('layouts.pdf', [
    'titre' => 'FACTURE',
    'ref' => $facture->numero_facture,
    'mention' => 'Facture n° ' . $facture->numero_facture . ' — Générée le ' . now()->format('d/m/Y à H:i'),
])

@section('contenu')
<div class="info-grid">
    <div class="info-col">
        <div class="info-box">
            <div class="label">Émetteur</div>
            <div class="value">BTP Manager SARL</div>
            <div style="font-size:8pt;color:#4a5568;margin-top:4px">
                Lomé — Togo<br>NIF : 1000000000<br>Tél : +228 22 XX XX XX
            </div>
        </div>
    </div>
    <div class="info-col">
        <div class="info-box">
            <div class="label">{{ $facture->type === 'client' ? 'Client' : 'Fournisseur' }}</div>
            <div class="value">{{ $facture->facturable?->nom ?? '—' }}</div>
            <div style="font-size:8pt;color:#4a5568;margin-top:4px">
                {{ $facture->facturable?->adresse ?? '' }}<br>
                NIF : {{ $facture->facturable?->nif ?? '—' }}
            </div>
        </div>
    </div>
</div>

<table style="width:100%;font-size:9pt;margin-bottom:12px">
    <tr>
        <td><strong>Date :</strong> {{ $facture->date_facture?->format('d/m/Y') }}</td>
        <td><strong>Échéance :</strong> {{ $facture->date_echeance?->format('d/m/Y') ?? '—' }}</td>
        <td class="text-right">
            <span class="badge badge-{{ $facture->statut === 'payee' ? 'success' : ($facture->statut === 'annulee' ? 'danger' : 'info') }}">
                {{ str_replace('_',' ', $facture->statut) }}
            </span>
        </td>
    </tr>
    @if($facture->projet)
        <tr><td colspan="3"><strong>Chantier :</strong> {{ $facture->projet->code }} — {{ $facture->projet->nom }}</td></tr>
    @endif
</table>

@if($facture->situation)
    <table class="data-table">
        <thead><tr><th>N° prix</th><th>Désignation</th><th>Unité</th><th class="text-right">Qté</th><th class="text-right">P.U.</th><th class="text-right">Montant HT</th></tr></thead>
        <tbody>
            @foreach($facture->situation->attachement->lignes as $ligne)
                @php $ld = $ligne->ligneDevis; @endphp
                <tr>
                    <td>{{ $ld->numero_prix ?? '—' }}</td>
                    <td>{{ $ld->designation }}</td>
                    <td class="text-center">{{ $ld->unite }}</td>
                    <td class="text-right">{{ number_format($ligne->quantite_periode, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($ld->prix_unitaire, 0, ',', ' ') }}</td>
                    <td class="text-right"><strong>{{ number_format($ligne->quantite_periode * $ld->prix_unitaire, 0, ',', ' ') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<div class="totals-box clearfix">
    <table>
        <tr class="subtotal"><td class="label-cell">Montant HT</td><td class="amount">{{ number_format($facture->montant_ht, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td class="label-cell">TVA</td><td class="amount">{{ number_format($facture->tva, 0, ',', ' ') }} FCFA</td></tr>
        <tr class="subtotal"><td class="label-cell">Montant TTC</td><td class="amount">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} FCFA</td></tr>
        @if($facture->retenue_garantie > 0)
            <tr><td class="label-cell">Retenue garantie</td><td class="amount">- {{ number_format($facture->retenue_garantie, 0, ',', ' ') }} FCFA</td></tr>
        @endif
        @if($facture->remboursement_avance > 0)
            <tr><td class="label-cell">Remb. avance</td><td class="amount">- {{ number_format($facture->remboursement_avance, 0, ',', ' ') }} FCFA</td></tr>
        @endif
        <tr class="total">
            <td class="label-cell" style="color:#fff">NET À PAYER</td>
            <td class="amount">{{ number_format($facture->net_a_payer, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>
</div>
<div style="clear:both"></div>

<div class="signatures">
    <div class="signature-box"><div class="sig-line">Le Client</div></div>
    <div class="signature-box"><div class="sig-line">La Direction</div></div>
</div>
@endsection