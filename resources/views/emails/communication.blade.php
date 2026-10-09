<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; background:#f0f2f5; margin:0; padding:20px; }
        .wrapper { max-width:640px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; }
        .header { background:#1c2530; color:#fff; padding:24px 32px; border-bottom:4px solid #f0900c; }
        .header h1 { margin:0; font-size:20px; font-weight:800; }
        .header .sub { font-size:11px; color:rgba(255,255,255,.6); letter-spacing:2px; text-transform:uppercase; margin-top:4px; }
        .body { padding:32px; color:#1a202c; font-size:14px; line-height:1.7; }
        .footer { background:#f8f9fc; padding:20px 32px; text-align:center; font-size:11px; color:#6b7a8f; border-top:1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>BTP Manager</h1>
            <div class="sub">Gestion intégrée des chantiers</div>
        </div>
        <div class="body">{!! $communication->corps !!}</div>
        <div class="footer">
            <strong>BTP Manager SARL</strong><br>
            Lomé — Togo · contact@btp-manager.tg<br>
            Cet email a été envoyé automatiquement.
        </div>
    </div>
</body>
</html>