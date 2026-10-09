{{-- Header avec méga-menu 6 menus --}}
<style>
.hbtp-root{position:sticky;top:0;z-index:1000;background:var(--btp-primary);box-shadow:0 2px 20px rgba(0,0,0,.15)}
.hbtp-top{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:0 24px;height:60px;background:linear-gradient(135deg,var(--btp-primary-dark),var(--btp-primary));border-bottom:1px solid rgba(255,255,255,.06)}
.hbtp-brand{display:flex;align-items:center;gap:12px;text-decoration:none}
.hbtp-brand-icon{width:40px;height:40px;border-radius:var(--btp-radius);background:linear-gradient(145deg,var(--btp-accent-light),var(--btp-accent));display:flex;align-items:center;justify-content:center;font-size:18px;color:var(--btp-primary);box-shadow:0 4px 14px rgba(240,144,12,.3)}
.hbtp-brand-title{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:#fff}
.hbtp-brand-sub{font-size:9px;color:rgba(255,255,255,.45);letter-spacing:1.2px;text-transform:uppercase;display:block;margin-top:2px}
.hbtp-top-right{display:flex;align-items:center;gap:6px}
.hbtp-icon-btn{width:38px;height:38px;border-radius:var(--btp-radius);background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.7);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all var(--btp-transition);position:relative;text-decoration:none}
.hbtp-icon-btn:hover{background:rgba(255,255,255,.14);color:#fff}
.hbtp-notif-dot{position:absolute;top:6px;right:6px;width:8px;height:8px;background:var(--btp-danger);border-radius:50%;border:2px solid var(--btp-primary);animation:pulse-dot 2s infinite}
@keyframes pulse-dot{0%{box-shadow:0 0 0 0 rgba(230,57,70,.5)}70%{box-shadow:0 0 0 6px rgba(230,57,70,0)}100%{box-shadow:0 0 0 0 rgba(230,57,70,0)}}
.hbtp-sep{width:1px;height:28px;background:rgba(255,255,255,.08);margin:0 4px}
.hbtp-avatar-wrap{position:relative}
.hbtp-avatar-btn{display:flex;align-items:center;gap:10px;padding:0 12px 0 6px;height:38px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);border-radius:var(--btp-radius);cursor:pointer}
.hbtp-avatar-circle{width:28px;height:28px;border-radius:50%;background:linear-gradient(145deg,var(--btp-accent-light),var(--btp-accent));color:var(--btp-primary);font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center}
.hbtp-avatar-name{font-size:13px;font-weight:600;color:#fff}
.hbtp-avatar-role{font-size:10px;color:rgba(255,255,255,.4);display:block}
.hbtp-user-drop{position:absolute;top:calc(100% + 8px);right:0;width:250px;background:#fff;border-radius:var(--btp-radius-lg);box-shadow:var(--btp-shadow-lg);border:1px solid var(--btp-border);display:none;z-index:9999}
.hbtp-avatar-wrap.open .hbtp-user-drop{display:block}
.hbtp-udrop-header{padding:16px 18px;background:linear-gradient(135deg,#f8f6f0,#f0ece4);border-bottom:1px solid var(--btp-border);border-radius:var(--btp-radius-lg) var(--btp-radius-lg) 0 0}
.hbtp-udrop-name{font-size:14px;font-weight:700;color:var(--btp-primary)}
.hbtp-udrop-email{font-size:11.5px;color:var(--btp-muted)}
.hbtp-udrop-role{display:inline-block;margin-top:6px;background:var(--btp-accent);color:#fff;font-size:9px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase}
.hbtp-udrop-item{display:flex;align-items:center;gap:12px;padding:10px 18px;font-size:13px;color:var(--btp-primary);text-decoration:none;transition:all var(--btp-transition);cursor:pointer;border:none;background:transparent;width:100%;text-align:left}
.hbtp-udrop-item:hover{background:#f8f6f0;padding-left:24px}
.hbtp-udrop-item i{color:var(--btp-accent-dark);width:18px;text-align:center}
.hbtp-udrop-item.danger{color:var(--btp-danger)}
.hbtp-udrop-div{height:1px;background:var(--btp-border);margin:4px 0}
.hbtp-nav{display:flex;align-items:center;padding:0 24px;height:48px;background:rgba(255,255,255,.03);border-top:1px solid rgba(255,255,255,.04)}
.hbtp-nav-items{display:flex;align-items:center;gap:2px;flex:1;height:100%}
.hnav-item{position:relative;display:flex;align-items:center;height:100%}
.hnav-trigger{display:flex;align-items:center;gap:8px;padding:0 16px;height:100%;color:rgba(255,255,255,.75);font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;background:transparent;border:none;transition:all var(--btp-transition);white-space:nowrap}
.hnav-trigger i{font-size:14px}
.hnav-trigger .caret{font-size:10px;opacity:.6}
.hnav-item.open .hnav-trigger .caret{transform:rotate(180deg)}
.hnav-trigger::after{content:'';position:absolute;bottom:0;left:50%;transform:translateX(-50%) scaleX(0);width:60%;height:3px;background:var(--btp-accent);border-radius:3px 3px 0 0;transition:transform var(--btp-transition)}
.hnav-item:hover .hnav-trigger::after,.hnav-item.open .hnav-trigger::after,.hnav-item.active .hnav-trigger::after{transform:translateX(-50%) scaleX(1)}
.hnav-item:hover .hnav-trigger,.hnav-item.open .hnav-trigger{color:#fff;background:rgba(255,255,255,.06)}
.hnav-item.active .hnav-trigger{color:var(--btp-accent-light);background:rgba(240,144,12,.08)}
.hnav-badge{display:inline-flex;align-items:center;justify-content:center;min-width:18px;height:18px;padding:0 6px;border-radius:20px;background:var(--btp-danger);color:#fff;font-size:10px;font-weight:800}
.hnav-drop{position:absolute;top:100%;left:0;min-width:260px;background:#fff;border-radius:0 0 var(--btp-radius-lg) var(--btp-radius-lg);box-shadow:0 20px 60px rgba(0,0,0,.15);border:1px solid var(--btp-border);border-top:3px solid var(--btp-accent);z-index:99999;padding:6px 0;opacity:0;visibility:hidden;transform:translateY(-4px);transition:all var(--btp-transition)}
.hnav-item.open .hnav-drop{opacity:1;visibility:visible;transform:translateY(0)}
.hnav-drop-title{padding:8px 16px 4px;font-size:10px;text-transform:uppercase;color:var(--btp-muted);letter-spacing:.8px;font-weight:800;display:flex;align-items:center;gap:8px}
.hnav-drop-title i{color:var(--btp-accent)}
.hnav-drop-item{display:flex;align-items:center;gap:12px;padding:9px 16px;font-size:13px;font-weight:500;color:var(--btp-primary);text-decoration:none;transition:all var(--btp-transition);border-left:3px solid transparent}
.hnav-drop-item:hover{background:#f8f6f0;padding-left:22px;border-left-color:var(--btp-accent)}
.hnav-drop-item i{color:var(--btp-muted);width:18px;text-align:center}
.hnav-drop-div{height:1px;background:var(--btp-border);margin:4px 0}
.hbtp-hamburger{display:none;flex-direction:column;gap:4px;justify-content:center;width:38px;height:38px;padding:8px;border-radius:var(--btp-radius);background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);cursor:pointer}
.hbtp-hamburger span{display:block;width:100%;height:2px;background:rgba(255,255,255,.85);border-radius:2px}
@media(max-width:768px){
    .hbtp-hamburger{display:flex}
    .hbtp-nav{height:0;overflow:hidden;flex-direction:column;padding:0;background:var(--btp-primary-dark)}
    .hbtp-nav.mobile-open{height:auto;padding:4px 0 12px;overflow:visible}
    .hbtp-nav-items{flex-direction:column;align-items:stretch;height:auto}
    .hnav-item{flex-direction:column;height:auto}
    .hnav-trigger{padding:12px 16px;justify-content:space-between;height:auto;width:100%}
    .hnav-trigger::after{display:none}
    .hnav-drop{position:static;box-shadow:none;border:none;background:rgba(0,0,0,.15);display:none;opacity:1;visibility:visible}
    .hnav-item.open .hnav-drop{display:block}
}
</style>

<div class="hbtp-root" id="header-top">
    <div class="hbtp-top">
        <a href="{{ route('dashboard') }}" class="hbtp-brand">
            <div class="hbtp-brand-icon"><i class="fas fa-helmet-safety"></i></div>
            <div>
                <span class="hbtp-brand-title">BTP Manager</span>
                <span class="hbtp-brand-sub">Gestion de chantiers</span>
            </div>
        </a>
        <div class="hbtp-top-right">
            <button class="hbtp-hamburger" id="hbtp-hamburger">
                <span></span><span></span><span></span>
            </button>
            <button class="hbtp-icon-btn" id="themeToggle" title="Thème">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            <button class="hbtp-icon-btn" id="btnSearch" title="Recherche (Ctrl+K)">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('notifications.index') }}" class="hbtp-icon-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                @if(auth()->check() && auth()->user()->unreadNotifications()->count() > 0)
                    <span class="hbtp-notif-dot"></span>
                @endif
            </a>
            <div class="hbtp-sep"></div>
            <div class="hbtp-avatar-wrap" id="hbtp-avatar-wrap">
                <div class="hbtp-avatar-btn" id="hbtp-avatar-btn">
                    <div class="hbtp-avatar-circle">{{ strtoupper(substr(auth()->user()->nom ?? 'AD', 0, 2)) }}</div>
                    <div>
                        <span class="hbtp-avatar-name">{{ auth()->user()->nom ?? 'Utilisateur' }}</span>
                        <span class="hbtp-avatar-role">{{ auth()->user()->role->nom ?? 'Rôle' }}</span>
                    </div>
                    <i class="fas fa-chevron-down" style="font-size:10px;color:rgba(255,255,255,.4)"></i>
                </div>
                <div class="hbtp-user-drop">
                    <div class="hbtp-udrop-header">
                        <div class="hbtp-udrop-name">{{ auth()->user()->nom ?? 'Nom' }}</div>
                        <div class="hbtp-udrop-email">{{ auth()->user()->email ?? '' }}</div>
                        <div class="hbtp-udrop-role">{{ auth()->user()->role->nom ?? 'Rôle' }}</div>
                    </div>
                    <a href="{{ route('profil.show') }}" class="hbtp-udrop-item"><i class="fas fa-user-circle"></i> Mon profil</a>
                    <a href="{{ route('profil.show') }}#mot-de-passe" class="hbtp-udrop-item"><i class="fas fa-key"></i> Changer mot de passe</a>
                    <div class="hbtp-udrop-div"></div>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0">
                        @csrf
                        <button type="submit" class="hbtp-udrop-item danger"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <nav class="hbtp-nav" id="hbtp-main-nav">
        <div class="hbtp-nav-items">
            {{-- 1. Tableau de bord --}}
            <div class="hnav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="hnav-trigger"><i class="fas fa-gauge-high"></i> Tableau de bord</a>
            </div>

            {{-- 2. Commercial et chantiers --}}
            <div class="hnav-item {{ request()->is('commercial/*','chantiers/*','situations/*') ? 'active' : '' }}">
                <a href="#" class="hnav-trigger"><i class="fas fa-diagram-project"></i> Commercial & Chantiers <i class="fas fa-chevron-down caret"></i></a>
                <div class="hnav-drop">
                    <div class="hnav-drop-title"><i class="fas fa-briefcase"></i> Commercial</div>
                    <a href="{{ route('commercial.clients.index') }}" class="hnav-drop-item"><i class="fas fa-user"></i> Clients</a>
                    <a href="{{ route('commercial.devis.index') }}" class="hnav-drop-item"><i class="fas fa-file-lines"></i> Devis</a>
                    <a href="{{ route('commercial.marches.index') }}" class="hnav-drop-item"><i class="fas fa-file-signature"></i> Marchés</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-helmet-safety"></i> Chantiers</div>
                    <a href="{{ route('projets.index') }}" class="hnav-drop-item"><i class="fas fa-list-ul"></i> Liste des chantiers</a>
                    <a href="{{ route('projets.journal.index') }}" class="hnav-drop-item"><i class="fas fa-book"></i> Journal de chantier</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-file-invoice-dollar"></i> Situations</div>
                    <a href="{{ route('situations.attachements.index') }}" class="hnav-drop-item"><i class="fas fa-ruler-combined"></i> Attachements</a>
                    <a href="{{ route('situations.situations.index') }}" class="hnav-drop-item"><i class="fas fa-file-invoice"></i> Situations de travaux</a>
                </div>
            </div>

            {{-- 3. Approvisionnement et logistique --}}
            <div class="hnav-item {{ request()->is('logistique/*') ? 'active' : '' }}">
                <a href="#" class="hnav-trigger"><i class="fas fa-boxes-stacked"></i> Logistique <i class="fas fa-chevron-down caret"></i></a>
                <div class="hnav-drop">
                    <div class="hnav-drop-title"><i class="fas fa-cart-shopping"></i> Achats</div>
                    <a href="{{ route('logistique.fournisseurs.index') }}" class="hnav-drop-item"><i class="fas fa-industry"></i> Fournisseurs</a>
                    <a href="{{ route('logistique.demandes-achat.index') }}" class="hnav-drop-item"><i class="fas fa-file-circle-plus"></i> Demandes d'achat</a>
                    <a href="{{ route('logistique.bons-commande.index') }}" class="hnav-drop-item"><i class="fas fa-file-invoice"></i> Bons de commande</a>
                    <a href="{{ route('logistique.livraisons.index') }}" class="hnav-drop-item"><i class="fas fa-dolly"></i> Livraisons</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-warehouse"></i> Stock</div>
                    <a href="{{ route('logistique.stocks.index') }}" class="hnav-drop-item"><i class="fas fa-layer-group"></i> Niveaux de stock</a>
                    <a href="{{ route('logistique.materiaux.index') }}" class="hnav-drop-item"><i class="fas fa-cubes"></i> Catalogue matériaux</a>
                    <a href="{{ route('logistique.entrepots.index') }}" class="hnav-drop-item"><i class="fas fa-warehouse"></i> Dépôts</a>
                    <a href="{{ route('logistique.transferts.index') }}" class="hnav-drop-item"><i class="fas fa-truck-ramp-box"></i> Transferts</a>
                    <a href="{{ route('logistique.inventaires.index') }}" class="hnav-drop-item"><i class="fas fa-clipboard-list"></i> Inventaires</a>
                </div>
            </div>

            {{-- 4. Matériel --}}
            <div class="hnav-item {{ request()->is('materiel/*') ? 'active' : '' }}">
                <a href="#" class="hnav-trigger"><i class="fas fa-truck"></i> Matériel <i class="fas fa-chevron-down caret"></i></a>
                <div class="hnav-drop">
                    <a href="{{ route('materiel.equipements.index') }}" class="hnav-drop-item"><i class="fas fa-truck"></i> Équipements</a>
                    <a href="{{ route('materiel.categories.index') }}" class="hnav-drop-item"><i class="fas fa-tags"></i> Catégories</a>
                    <a href="{{ route('materiel.maintenances.index') }}" class="hnav-drop-item"><i class="fas fa-screwdriver-wrench"></i> Maintenances</a>
                </div>
            </div>

            {{-- 5. RH et Paie --}}
            <div class="hnav-item {{ request()->is('rh/*') ? 'active' : '' }}">
                <a href="#" class="hnav-trigger"><i class="fas fa-users"></i> RH & Paie <i class="fas fa-chevron-down caret"></i></a>
                <div class="hnav-drop">
                    <div class="hnav-drop-title"><i class="fas fa-id-badge"></i> Personnel</div>
                    <a href="{{ route('rh.employes.index') }}" class="hnav-drop-item"><i class="fas fa-id-badge"></i> Employés</a>
                    <a href="{{ route('rh.contrats.index') }}" class="hnav-drop-item"><i class="fas fa-file-signature"></i> Contrats</a>
                    <a href="{{ route('rh.referentiels.index') }}" class="hnav-drop-item"><i class="fas fa-sitemap"></i> Référentiels</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-clock"></i> Suivi</div>
                    <a href="{{ route('rh.conges.index') }}" class="hnav-drop-item"><i class="fas fa-umbrella-beach"></i> Congés</a>
                    <a href="{{ route('rh.pointages.index') }}" class="hnav-drop-item"><i class="fas fa-clock"></i> Pointages</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-money-check-dollar"></i> Paie</div>
                    <a href="{{ route('rh.periodes-paie.index') }}" class="hnav-drop-item"><i class="fas fa-calendar-week"></i> Périodes</a>
                    <a href="{{ route('rh.bulletins.index') }}" class="hnav-drop-item"><i class="fas fa-file-invoice-dollar"></i> Bulletins</a>
                    <a href="{{ route('rh.avances.index') }}" class="hnav-drop-item"><i class="fas fa-money-check-dollar"></i> Avances</a>
                </div>
            </div>

            {{-- 6. Finances et Sous-traitance --}}
            <div class="hnav-item {{ request()->is('finances/*','sous-traitance/*') ? 'active' : '' }}">
                <a href="#" class="hnav-trigger"><i class="fas fa-calculator"></i> Finances <i class="fas fa-chevron-down caret"></i></a>
                <div class="hnav-drop">
                    <div class="hnav-drop-title"><i class="fas fa-file-invoice-dollar"></i> Facturation</div>
                    <a href="{{ route('finances.factures.index') }}" class="hnav-drop-item"><i class="fas fa-file-invoice-dollar"></i> Factures</a>
                    <a href="{{ route('finances.paiements.index') }}" class="hnav-drop-item"><i class="fas fa-credit-card"></i> Paiements</a>
                    <a href="{{ route('finances.depenses.index') }}" class="hnav-drop-item"><i class="fas fa-money-bill-wave"></i> Dépenses</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-building-columns"></i> Trésorerie</div>
                    <a href="{{ route('finances.caisses.index') }}" class="hnav-drop-item"><i class="fas fa-cash-register"></i> Caisses</a>
                    <a href="{{ route('finances.comptes-bancaires.index') }}" class="hnav-drop-item"><i class="fas fa-building-columns"></i> Comptes bancaires</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-book"></i> Comptabilité</div>
                    <a href="{{ route('finances.ecritures.index') }}" class="hnav-drop-item"><i class="fas fa-pen-to-square"></i> Écritures</a>
                    <a href="{{ route('finances.plan-comptable.index') }}" class="hnav-drop-item"><i class="fas fa-book"></i> Plan comptable</a>
                    <a href="{{ route('finances.exercices.index') }}" class="hnav-drop-item"><i class="fas fa-calendar-alt"></i> Exercices</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-handshake"></i> Sous-traitance</div>
                    <a href="{{ route('soustraitance.soustraitants.index') }}" class="hnav-drop-item"><i class="fas fa-address-book"></i> Sous-traitants</a>
                    <a href="{{ route('soustraitance.contrats.index') }}" class="hnav-drop-item"><i class="fas fa-file-contract"></i> Contrats</a>
                </div>
            </div>

            {{-- 7. QHSE et Administration --}}
            <div class="hnav-item {{ request()->is('qhse/*','admin/*','communications/*') ? 'active' : '' }}">
                <a href="#" class="hnav-trigger"><i class="fas fa-shield-halved"></i> QHSE & Admin <i class="fas fa-chevron-down caret"></i></a>
                <div class="hnav-drop">
                    <div class="hnav-drop-title"><i class="fas fa-shield-halved"></i> QHSE</div>
                    <a href="{{ route('qhse.incidents.index') }}" class="hnav-drop-item"><i class="fas fa-triangle-exclamation"></i> Incidents</a>
                    <a href="{{ route('qhse.causeries.index') }}" class="hnav-drop-item"><i class="fas fa-comments"></i> Causeries</a>
                    <a href="{{ route('qhse.non-conformites.index') }}" class="hnav-drop-item"><i class="fas fa-clipboard-check"></i> Non-conformités</a>
                    <a href="{{ route('qhse.pv-receptions.index') }}" class="hnav-drop-item"><i class="fas fa-file-signature"></i> PV de réception</a>
                    <div class="hnav-drop-div"></div>
                    <div class="hnav-drop-title"><i class="fas fa-cog"></i> Administration</div>
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.users.index') }}" class="hnav-drop-item"><i class="fas fa-users-cog"></i> Utilisateurs</a>
                        <a href="{{ route('admin.roles.index') }}" class="hnav-drop-item"><i class="fas fa-user-shield"></i> Rôles</a>
                        <a href="{{ route('admin.parametres.index') }}" class="hnav-drop-item"><i class="fas fa-sliders-h"></i> Paramètres</a>
                        <a href="{{ route('admin.journal.index') }}" class="hnav-drop-item"><i class="fas fa-history"></i> Journal</a>
                    @endif
                    <a href="{{ route('communications.index') }}" class="hnav-drop-item"><i class="fas fa-envelope"></i> Communications</a>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
$(function(){
    var isMobile = function(){ return $(window).width() <= 768; };
    function closeAll(){ $('.hnav-item.open').removeClass('open').find('> .hnav-trigger').attr('aria-expanded','false'); }

    $('.hnav-item').each(function(){
        var $item = $(this), $t = $item.find('> .hnav-trigger');
        $item.on('mouseenter', function(){ if(isMobile()) return; closeAll(); $item.addClass('open'); });
        $item.on('mouseleave', function(){ if(isMobile()) return; $item.removeClass('open'); });
        $t.on('click', function(e){
            if(!isMobile()) return;
            e.preventDefault();
            var was = $item.hasClass('open');
            closeAll();
            if(!was) $item.addClass('open');
        });
    });

    var $aw = $('#hbtp-avatar-wrap'), $ab = $('#hbtp-avatar-btn');
    $aw.on('mouseenter', function(){ $aw.addClass('open'); })
       .on('mouseleave', function(){ $aw.removeClass('open'); });
    $ab.on('click', function(e){ e.stopPropagation(); $aw.toggleClass('open'); });
    $(document).on('click', function(e){ if(!$(e.target).closest($aw).length) $aw.removeClass('open'); });

    $('#hbtp-hamburger').on('click', function(){
        var o = $('#hbtp-main-nav').toggleClass('mobile-open').hasClass('mobile-open');
        $(this).toggleClass('open', o);
    });

    function applyTheme(d){ $('html').toggleClass('dark-theme', d); $('#themeIcon').attr('class', d ? 'fas fa-sun' : 'fas fa-moon'); try{localStorage.setItem('btp-theme',d?'dark':'light');}catch(e){} }
    try{ var s = localStorage.getItem('btp-theme'); if(s) applyTheme(s==='dark'); }catch(e){}
    $('#themeToggle').on('click', function(){ applyTheme(!$('html').hasClass('dark-theme')); });

    $(document).on('keydown', function(e){
        if((e.ctrlKey||e.metaKey) && e.key==='k'){ e.preventDefault(); $('#btnSearch').trigger('click'); }
    });
});
</script>