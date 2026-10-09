<div class="search-modal" id="searchModal">
    <div class="search-modal-content">
        <div class="search-modal-header">
            <div class="search-icon-wrapper"><i class="fas fa-search"></i></div>
            <input type="text" class="search-modal-input" id="searchInput" placeholder="Rechercher un chantier, client, employé, facture…" autocomplete="off">
            <span class="search-modal-shortcut"><kbd>⌘</kbd> <kbd>K</kbd></span>
            <button class="search-modal-close" id="searchModalClose"><i class="fas fa-times"></i></button>
        </div>
        <div class="search-modal-body" id="searchResults">
            <div class="search-empty">
                <span class="empty-icon"><i class="fas fa-search-plus"></i></span>
                <div class="empty-title">Recherche rapide</div>
                <div class="empty-sub">Commencez à taper pour rechercher</div>
            </div>
        </div>
    </div>
</div>
<style>
.search-modal{position:fixed;inset:0;z-index:10000;background:rgba(10,22,40,.8);backdrop-filter:blur(12px);display:flex;align-items:flex-start;justify-content:center;padding-top:10vh;opacity:0;visibility:hidden;transition:opacity .3s}
.search-modal.open{opacity:1;visibility:visible}
.search-modal-content{background:#fff;border-radius:16px;max-width:720px;width:92%;box-shadow:0 40px 80px rgba(0,0,0,.3);overflow:hidden;transform:translateY(-30px) scale(.96);transition:transform .35s cubic-bezier(.34,1.56,.64,1);opacity:0}
.search-modal.open .search-modal-content{transform:translateY(0) scale(1);opacity:1}
.search-modal-header{display:flex;align-items:center;gap:14px;padding:18px 24px;background:linear-gradient(135deg,var(--btp-primary-dark),var(--btp-primary));border-bottom:2px solid var(--btp-accent)}
.search-icon-wrapper{width:40px;height:40px;background:rgba(240,144,12,.15);border-radius:10px;color:var(--btp-accent);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.search-modal-input{flex:1;border:none;background:rgba(255,255,255,.08);border-radius:10px;font-size:1.05rem;color:#fff;outline:none;padding:12px 18px}
.search-modal-input::placeholder{color:rgba(255,255,255,.5)}
.search-modal-shortcut{display:inline-flex;gap:4px;padding:4px 10px;background:rgba(255,255,255,.08);border-radius:6px;font-size:.65rem;color:rgba(255,255,255,.4)}
.search-modal-shortcut kbd{background:rgba(255,255,255,.12);padding:2px 6px;border-radius:4px;font-size:.6rem;color:rgba(255,255,255,.6)}
.search-modal-close{width:40px;height:40px;background:rgba(255,255,255,.06);border:none;border-radius:10px;color:rgba(255,255,255,.6);cursor:pointer}
.search-modal-body{padding:8px 0;max-height:55vh;overflow-y:auto}
.search-result-section{padding:8px 20px 4px;font-size:.65rem;text-transform:uppercase;color:var(--btp-muted);font-weight:700;letter-spacing:.8px;border-bottom:1px solid var(--btp-border)}
.search-result-item{display:flex;align-items:center;gap:14px;padding:12px 20px;cursor:pointer;text-decoration:none;color:inherit;border-bottom:1px solid #f0f2f5}
.search-result-item:hover{background:#f8f6f0;padding-left:26px}
.search-result-icon{width:42px;height:42px;background:linear-gradient(135deg,var(--btp-primary-dark),var(--btp-primary));border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--btp-accent);flex-shrink:0}
.search-result-info{flex:1;min-width:0}
.search-result-title{font-weight:600;font-size:.92rem;color:var(--btp-primary);display:flex;gap:8px;flex-wrap:wrap}
.search-result-title .badge-category{background:var(--btp-accent);color:#fff;font-size:.55rem;font-weight:700;padding:2px 10px;border-radius:20px;text-transform:uppercase}
.search-result-sub{font-size:.78rem;color:var(--btp-muted);margin-top:2px}
.search-empty{padding:48px 20px;text-align:center;color:var(--btp-muted)}
.search-empty .empty-icon{font-size:3.5rem;color:#dce4ea;margin-bottom:16px;display:block}
.search-empty .empty-title{font-size:1.1rem;font-weight:600;color:var(--btp-primary);margin-bottom:4px}
</style>
<script>
$(function(){
    var $modal = $('#searchModal'), $input = $('#searchInput'), $results = $('#searchResults');
    function renderEmpty(){
        $results.html('<div class="search-empty"><span class="empty-icon"><i class="fas fa-search-plus"></i></span><div class="empty-title">Recherche rapide</div><div class="empty-sub">Commencez à taper…</div></div>');
    }
    function doSearch(q){
        if(!q || q.length < 2){ renderEmpty(); return; }
        $.get('{{ route("search.global") }}', { q: q }, function(results){
            if(!results.length){ $results.html('<div class="search-empty"><div class="empty-title">Aucun résultat</div></div>'); return; }
            var grouped = {};
            $.each(results, function(i, r){ (grouped[r.category] = grouped[r.category] || []).push(r); });
            var html = '';
            $.each(grouped, function(cat, items){
                html += '<div class="search-result-section"><i class="fas fa-folder"></i> ' + cat + '</div>';
                $.each(items, function(i, item){
                    html += '<a href="' + item.url + '" class="search-result-item"><div class="search-result-icon"><i class="fas ' + item.icon + '"></i></div><div class="search-result-info"><div class="search-result-title">' + item.title + ' <span class="badge-category">' + item.category + '</span></div><div class="search-result-sub">' + item.subtitle + '</div></div></a>';
                });
            });
            $results.html(html);
        });
    }
    var timer;
    $input.on('input', function(){ clearTimeout(timer); var v = $(this).val(); timer = setTimeout(function(){ doSearch(v); }, 300); });
    function openSearch(){ $modal.addClass('open'); $('body').css('overflow','hidden'); setTimeout(function(){ $input.trigger('focus').select(); }, 150); renderEmpty(); $input.val(''); }
    function closeSearch(){ $modal.removeClass('open'); $('body').css('overflow',''); }
    $(document).on('keydown', function(e){
        if((e.ctrlKey||e.metaKey) && e.key==='k'){ e.preventDefault(); openSearch(); }
        if(e.key==='Escape' && $modal.hasClass('open')) closeSearch();
    });
    $('#searchModalClose').on('click', closeSearch);
    $modal.on('click', function(e){ if(e.target === this) closeSearch(); });
    $('#btnSearch').on('click', openSearch);
});
</script>