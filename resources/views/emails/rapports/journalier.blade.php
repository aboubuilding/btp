<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; background:#f0f2f5; margin:0; padding:20px; }
        .wrapper { max-width:640px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; }
        .header { background:#1c2530; color:#fff; padding:24px 32px; border-bottom:4px solid #f0900c; }
        .kpi { display:grid; grid-template-columns:1fr 1fr; gap:12px; padding:20px; }
        .kpi-card { padding:16px; background:#f8f9fc; border-radius:8px; border-left:3px solid #f0900c; }
        .kpi-label { font-size:11px; text-transform:uppercase; color:#6b7a8f; font-weight:700; }
        .kpi-value { font-size:20px; font-weight:800; color:#1c2530; margin-top:4px; }
        .footer { background:#f8f9fc; padding:20px 32px; text-align:center; font-size:11px; color:#6b7a8f; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>📊 Rapport journalier</h1>
            <div style="font-size:11px;color:rgba(255,255,255,.6);margin-top:4px">{{ $stats['date'] }}</div>
        </div>
        <div class="kpi">
            <div class="kpi-card"><div class="kpi-label">Chantiers actifs</div><div class="kpi-value">{{ $stats['chantiers_actifs'] }}</div></div>
            <div class="kpi-card"><div class="kpi-label">Tâches en retard</div><div class="kpi-value">{{ $stats['taches_retard'] }}</div></div>
            <div class="kpi-card"><div class="kpi-label">Factures échues</div><div class="kpi-value">{{ $stats['factures_echues'] }}</div></div>
            <div class="kpi-card"><div class="kpi-label">Incidents (mois)</div><div class="kpi-value">{{ $stats['incidents_mois'] }}</div></div>
            <div class="kpi-card"><div class="kpi-label">Alertes stock</div><div class="kpi-value">{{ $stats['stock_alertes'] }}</div></div>
            <div class="kpi-card"><div class="kpi-label">CA du mois</div><div class="kpi-value">{{ number_format($stats['ca_mois'], 0, ',', ' ') }} FCFA</div></div>
        </div>
        <div class="footer"><strong>BTP Manager</strong> — Rapport automatique</div>
    </div>
</body>
</html>