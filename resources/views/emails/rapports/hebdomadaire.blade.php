<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; background:#f0f2f5; margin:0; padding:20px; }
        .wrapper { max-width:640px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; }
        .header { background:#1c2530; color:#fff; padding:24px 32px; border-bottom:4px solid #f0900c; }
        .body { padding:24px 32px; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:8px 12px; text-align:left; border-bottom:1px solid #e2e8f0; font-size:13px; }
        th { background:#f8f9fc; font-size:11px; text-transform:uppercase; color:#6b7a8f; }
        .footer { padding:20px 32px; text-align:center; font-size:11px; color:#6b7a8f; background:#f8f9fc; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>📈 Rapport hebdomadaire</h1>
            <div style="font-size:11px;color:rgba(255,255,255,.6);margin-top:4px">Semaine du {{ $stats['semaine'] }}</div>
        </div>
        <div class="body">
            <table>
                <tr><th>Indicateur</th><th>Valeur</th></tr>
                <tr><td>CA facturé</td><td><strong>{{ number_format($stats['ca_facture'], 0, ',', ' ') }} FCFA</strong></td></tr>
                <tr><td>CA encaissé</td><td>{{ number_format($stats['ca_encaisse'], 0, ',', ' ') }} FCFA</td></tr>
                <tr><td>Heures travaillées</td><td>{{ number_format($stats['heures_travaillees'], 0, ',', ' ') }} h</td></tr>
                <tr><td>Incidents</td><td>{{ $stats['incidents'] }}</td></tr>
                <tr><td>Chantiers actifs</td><td>{{ $stats['chantiers_actifs'] }}</td></tr>
            </table>

            <h3 style="margin-top:24px;font-size:14px;color:#1c2530">🏗️ Top chantiers</h3>
            <table>
                <tr><th>Code</th><th>Nom</th><th>Avancement</th></tr>
                @foreach($stats['top_chantiers'] as $c)
                    <tr><td>{{ $c->code }}</td><td>{{ $c->nom }}</td><td>{{ $c->pourcentage_avancement }}%</td></tr>
                @endforeach
            </table>
        </div>
        <div class="footer"><strong>BTP Manager</strong> — Direction Générale</div>
    </div>
</body>
</html>