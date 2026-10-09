<footer class="btp-footer">
    <div class="footer-left">
        <div class="brand">
            <div class="brand-icon"><i class="fas fa-helmet-safety"></i></div>
            <strong>BTP <span>Manager</span></strong>
        </div>
        <div class="subtitle">Plateforme de gestion de chantiers et de ressources BTP</div>
        <div class="copyright"><i class="far fa-copyright"></i> {{ date('Y') }} BTP Manager — Tous droits réservés</div>
    </div>
    <div class="footer-right">
        <a href="{{ route('dashboard') }}" class="footer-link"><i class="fas fa-home"></i> Accueil</a>
        <span class="separator">|</span>
        <a href="{{ route('communications.index') }}" class="footer-link"><i class="fas fa-envelope"></i> Communications</a>
        <span class="separator">|</span>
        <div class="version-badge"><i class="fas fa-code-branch"></i> v1.0.0</div>
    </div>
</footer>
<style>
.btp-footer{background:linear-gradient(135deg,#060e1a,#0a1628);color:rgba(255,255,255,.8);border-top:2px solid #d4a745;padding:24px 32px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;font-size:.85rem;margin-top:auto;width:100%;position:relative;overflow:hidden}
.btp-footer::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent 0%,#d4a745 20%,#f0d48a 50%,#d4a745 80%,transparent 100%)}
.btp-footer .footer-left{display:flex;flex-direction:column;gap:4px}
.btp-footer .footer-left .brand{display:flex;align-items:center;gap:10px}
.btp-footer .footer-left .brand-icon{width:32px;height:32px;background:rgba(212,167,69,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;color:#d4a745}
.btp-footer .footer-left strong{color:#fff;font-weight:700;font-size:1rem}
.btp-footer .footer-left strong span{color:#d4a745}
.btp-footer .footer-left .subtitle{font-size:.7rem;opacity:.5;text-transform:uppercase}
.btp-footer .footer-left .copyright{font-size:.75rem;opacity:.6}
.btp-footer .footer-left .copyright i{color:#d4a745;margin-right:4px}
.btp-footer .footer-right{display:flex;align-items:center;gap:20px;flex-wrap:wrap}
.btp-footer .footer-right .footer-link{color:rgba(255,255,255,.6);text-decoration:none;font-weight:500;font-size:.82rem;display:flex;align-items:center;gap:6px}
.btp-footer .footer-right .footer-link:hover{color:#f0d48a}
.btp-footer .footer-right .separator{color:rgba(255,255,255,.1)}
.btp-footer .footer-right .version-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(212,167,69,.12);color:#f0d48a;padding:4px 14px;border-radius:20px;font-size:.7rem;font-weight:600}
@media(max-width:768px){.btp-footer{flex-direction:column;text-align:center}.btp-footer .footer-right{justify-content:center}.btp-footer .footer-right .separator{display:none}}
</style>