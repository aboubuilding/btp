@extends('layouts.pdf', [
    'titre' => 'BULLETIN DE PAIE',
    'ref' => $bulletin->periode->libelle,
    'mention' => 'Bulletin ' . $bulletin->employe->matricule . ' — ' . $bulletin->periode->libelle,
])

@section('contenu')
<div class="info-grid">
    <div class="info-col">
        <div class="info-box">
            <div class="label">Employeur</div>
            <div class="value">BTP Manager SARL</div>
            <div style="font-size:8pt;color:#4a5568;margin-top:4px">Lomé — Togo · NIF : 1000000000</div>
        </div>
    </div>
    <div class="info-col">
        <div class="info-box">
            <div class="label">Salarié</div>
            <div class="value">{{ $bulletin->employe->nom_complet }}</div>
            <div style="font-size:8pt;color:#4a5568;margin-top:4px">
                Matricule : {{ $bulletin->employe->matricule }}<br>
                Poste : {{ $bulletin->employe->poste?->nom ?? '—' }}
            </div>
        </div>
    </div>
</div>

<table style="width:100%;font-size:9pt;margin-bottom:12px">
    <tr>
        <td><strong>Période :</strong> {{ $bulletin->periode->libelle }}</td>
        <td><strong>Heures :</strong> {{ $bulletin->heures_travaillees }} h</td>
    </tr>
</table>

<table class="data-table">
    <thead><tr><th>Rubrique</th><th class="text-right">Base</th><th class="text-right">Taux</th><th class="text-right">Montant</th></tr></thead>
    <tbody>
        <tr><td><strong>Salaire de base</strong></td><td>—</td><td>—</td><td class="text-right"><strong>{{ number_format($bulletin->salaire_base, 0, ',', ' ') }}</strong></td></tr>
        <tr style="background:#f8f9fc;font-weight:700"><td>SALAIRE BRUT</td><td colspan="2"></td><td class="text-right">{{ number_format($bulletin->brut, 0, ',', ' ') }}</td></tr>
        <tr><td>Cotisations salariales</td><td class="text-right">{{ number_format($bulletin->brut, 0, ',', ' ') }}</td><td class="text-right">5 %</td><td class="text-right">- {{ number_format($bulletin->cotisations_salariales, 0, ',', ' ') }}</td></tr>
        <tr><td>Impôt sur le revenu</td><td>—</td><td>—</td><td class="text-right">- {{ number_format($bulletin->impot_revenu, 0, ',', ' ') }}</td></tr>
        @if($bulletin->avances_deduites > 0)
            <tr><td>Avances déduites</td><td>—</td><td>—</td><td class="text-right">- {{ number_format($bulletin->avances_deduites, 0, ',', ' ') }}</td></tr>
        @endif
    </tbody>
</table>

<div class="totals-box clearfix">
    <table>
        <tr class="total"><td class="label-cell" style="color:#fff">NET À PAYER</td><td class="amount">{{ number_format($bulletin->net_a_payer, 0, ',', ' ') }} FCFA</td></tr>
    </table>
</div>
<div style="clear:both"></div>

<div class="signatures">
    <div class="signature-box"><div class="sig-line">L'Employeur</div></div>
    <div class="signature-box"><div class="sig-line">Le Salarié</div></div>
</div>
@endsection