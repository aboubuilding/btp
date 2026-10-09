<div class="page-header-bar">
    <div class="page-header-left">
        <h1 class="page-title-main">
            <span class="title-icon"><i class="fas @yield('page_icon', 'fa-helmet-safety')"></i></span>
            @yield('page_title')
        </h1>
        @hasSection('breadcrumb')
            <nav aria-label="Fil d'Ariane">
                <ul class="breadcrumb-custom">@yield('breadcrumb')</ul>
            </nav>
        @endif
    </div>
    <div class="page-header-right">@yield('page_actions')</div>
</div>
<style>
.page-header-bar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;padding:20px 30px;margin-bottom:24px;background:var(--btp-card-bg);border-bottom:1px solid var(--btp-border)}
.page-header-left{display:flex;align-items:center;gap:16px;flex-wrap:wrap}
.page-title-main{display:flex;align-items:center;gap:12px;margin:0;font-weight:700;font-size:1.35rem;color:var(--btp-ink);letter-spacing:-0.3px}
.title-icon{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:rgba(240,144,12,.12);border-radius:10px;color:var(--btp-accent);font-size:1.1rem}
.breadcrumb-custom{display:flex;align-items:center;gap:8px;list-style:none;padding:0;margin:0;font-size:.82rem;font-weight:500;color:var(--btp-muted);flex-wrap:wrap}
.breadcrumb-custom li{display:flex;align-items:center;gap:8px}
.breadcrumb-custom li:not(:last-child)::after{content:'/';opacity:.5}
.breadcrumb-custom a{color:var(--btp-secondary);text-decoration:none}
.breadcrumb-custom a:hover{color:var(--btp-accent)}
.breadcrumb-custom .active{color:var(--btp-primary);font-weight:600}
.page-header-right{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
@media(max-width:991.98px){.page-header-bar{padding:16px 20px;flex-direction:column;align-items:stretch}.page-header-left{flex-direction:column;align-items:stretch}.page-title-main{font-size:1.1rem}}
</style>