<style>
:root {
    --btp-primary:#1c2530; --btp-primary-dark:#121924; --btp-secondary:#2a3644;
    --btp-accent:#f0900c; --btp-accent-light:#ffb648; --btp-accent-dark:#c96f00;
    --btp-success:#2d8f5e; --btp-danger:#e63946; --btp-warning:#b7950b; --btp-info:#2b6cb0;
    --btp-ink:#1a202c; --btp-muted:#6b7a8f; --btp-bg:#eef0f3;
    --btp-card-bg:#fff; --btp-border:#e2e8f0; --btp-border-soft:#f0f2f5;
    --btp-shadow:0 2px 12px rgba(10,15,22,.06);
    --btp-shadow-hover:0 8px 30px rgba(10,15,22,.10);
    --btp-shadow-lg:0 25px 60px rgba(10,15,22,.16);
    --btp-radius-sm:8px; --btp-radius:12px; --btp-radius-lg:16px; --btp-radius-xl:20px;
    --btp-transition:.25s cubic-bezier(.4,0,.2,1);
    --btp-font:'Kumbh Sans',sans-serif;
}
*{box-sizing:border-box}
body{font-family:var(--btp-font);background:var(--btp-bg);color:var(--btp-ink);-webkit-font-smoothing:antialiased}
.page-wrapper{display:flex;flex-direction:column;min-height:100vh;padding-top:108px}
.content-area{flex:1;padding:0 30px 30px}
@media(max-width:991.98px){.page-wrapper{padding-top:96px}.content-area{padding:0 16px 16px}}
@media(max-width:480px){.page-wrapper{padding-top:88px}}
.text-btp-primary{color:var(--btp-primary)!important}
.text-btp-accent{color:var(--btp-accent)!important}
.text-btp-success{color:var(--btp-success)!important}
.text-btp-danger{color:var(--btp-danger)!important}
.text-btp-warning{color:var(--btp-warning)!important}
.text-btp-info{color:var(--btp-info)!important}
.text-btp-muted{color:var(--btp-muted)!important}
.bg-btp-primary{background:var(--btp-primary)!important}
.bg-btp-accent{background:var(--btp-accent)!important}
.bg-btp-light{background:var(--btp-bg)!important}
.fw-300{font-weight:300}.fw-400{font-weight:400}.fw-500{font-weight:500}
.fw-600{font-weight:600}.fw-700{font-weight:700}.fw-800{font-weight:800}
.rounded-btp{border-radius:var(--btp-radius)!important}
.rounded-btp-lg{border-radius:var(--btp-radius-lg)!important}
.shadow-btp{box-shadow:var(--btp-shadow)!important}
.shadow-btp-hover:hover{box-shadow:var(--btp-shadow-hover)!important}
::-webkit-scrollbar{width:6px;height:6px}
::-webkit-scrollbar-thumb{background:var(--btp-muted);border-radius:8px}
::-webkit-scrollbar-thumb:hover{background:var(--btp-secondary)}
::selection{background:var(--btp-accent);color:#fff}
.dark-theme{
    --btp-primary:#0d1117;--btp-primary-dark:#06080a;--btp-ink:#e6edf3;
    --btp-muted:#8b949e;--btp-bg:#0d1117;--btp-card-bg:#161b22;
    --btp-border:#30363d;--btp-border-soft:#1c2333;
}
.btn-icon-sm{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:transparent;border:1px solid var(--btp-border);border-radius:8px;color:var(--btp-muted);text-decoration:none;transition:all var(--btp-transition);cursor:pointer}
.btn-icon-sm:hover{background:var(--btp-accent);border-color:var(--btp-accent);color:#fff;transform:translateY(-1px)}
.progress-btp{height:8px;background:var(--btp-border-soft);border-radius:8px;overflow:hidden}
.progress-btp .progress-bar{border-radius:8px}
.list-btp{list-style:none;padding:0;margin:0}
.list-btp li{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--btp-border-soft)}
.list-btp li:last-child{border-bottom:none}
.alert-info-btp{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;padding:10px 14px;border-radius:8px;font-size:.85rem;display:flex;gap:8px;align-items:flex-start}
.nav-tabs-btp{display:flex;gap:4px;border-bottom:2px solid var(--btp-border);list-style:none;padding:0;margin:0}
.nav-tabs-btp .nav-link{display:flex;align-items:center;gap:8px;padding:12px 18px;background:transparent;border:none;font-family:var(--btp-font);font-weight:600;font-size:.85rem;color:var(--btp-muted);cursor:pointer;border-bottom:3px solid transparent;transition:all var(--btp-transition);margin-bottom:-2px}
.nav-tabs-btp .nav-link:hover{color:var(--btp-ink)}
.nav-tabs-btp .nav-link.active{color:var(--btp-accent);border-bottom-color:var(--btp-accent)}
@media(max-width:576px){.nav-tabs-btp{overflow-x:auto}}
</style>