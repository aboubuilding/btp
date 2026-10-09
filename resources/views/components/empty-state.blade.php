@props(['icon'=>'fa-inbox','title'=>'Aucun élément','message'=>null,'action'=>null])
<div class="btp-empty-state">
    <div class="empty-icon-wrap"><i class="fas {{ $icon }}"></i></div>
    <h4 class="empty-title">{{ $title }}</h4>
    @if($message)<p class="empty-message">{{ $message }}</p>@endif
    @if($action)<div>{!! $action !!}</div>@endif
</div>
<style>
.btp-empty-state{padding:48px 24px;text-align:center;background:var(--btp-card-bg);border:2px dashed var(--btp-border);border-radius:var(--btp-radius-lg)}
.empty-icon-wrap{width:80px;height:80px;margin:0 auto 20px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:rgba(240,144,12,.08);color:var(--btp-accent);font-size:2rem}
.empty-title{font-size:1.1rem;font-weight:700;color:var(--btp-ink);margin-bottom:6px}
.empty-message{color:var(--btp-muted);font-size:.9rem;margin-bottom:20px}
</style>