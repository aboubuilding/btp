<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; background:#eef0f3; margin:0; padding:20px; }
        .wrapper { max-width:600px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.08); }
        .header { padding:24px 32px; color:#fff; }
        .header.info { background:linear-gradient(135deg,#2b6cb0,#1a4a85); }
        .header.success { background:linear-gradient(135deg,#2d8f5e,#1e6142); }
        .header.warning { background:linear-gradient(135deg,#b7950b,#8a6e08); }
        .header.danger { background:linear-gradient(135deg,#e63946,#a52530); }
        .header h1 { margin:0; font-size:20px; font-weight:800; }
        .header .sub { font-size:11px; color:rgba(255,255,255,.75); letter-spacing:2px; text-transform:uppercase; margin-top:4px; }
        .body { padding:32px; color:#1a202c; font-size:14px; line-height:1.7; }
        .meta { background:#f8f9fc; border-left:4px solid #f0900c; padding:12px 16px; border-radius:6px; font-size:12px; color:#4a5568; margin:16px 0; }
        .meta dt { font-weight:700; color:#1c2530; text-transform:uppercase; font-size:10px; }
        .meta dd { margin:0 0 8px; }
        .cta { display:inline-block; padding:12px 24px; background:#f0900c; color:#fff; text-decoration:none; border-radius:8px; font-weight:700; font-size:13px; margin-top:16px; }
        .footer { padding:20px 32px; text-align:center; font-size:11px; color:#6b7a8f; background:#f8f9fc; border-top:1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header {{ $couleur }}">
            <h1>{{ $titre }}</h1>
            <div class="sub">BTP Manager — Notification</div>
        </div>
        <div class="body">
            <p>{{ $message }}</p>
            @if(!empty($meta))
                <div class="meta">
                    <dl style="margin:0">
                        @foreach($meta as $cle => $valeur)
                            <dt>{{ $cle }}</dt>
                            <dd>{{ $valeur }}</dd>
                        @endforeach
                    </dl>
                </div>
            @endif
            @if($url && $url !== '#')
                <a href="{{ $url }}" class="cta">{{ $actionLabel ?? 'Voir dans BTP Manager' }} →</a>
            @endif
        </div>
        <div class="footer">
            <strong>BTP Manager SARL</strong><br>
            Lomé — Togo · contact@btp-manager.tg<br>
            Email automatique — merci de ne pas répondre.
        </div>
    </div>
</body>
</html>