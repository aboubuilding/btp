@props(['label','value','icon'=>'fa-chart-line','color'=>'accent','trend'=>null,'trendUp'=>true,'sub'=>null,'href'=>null])
@php $tag = $href ? 'a' : 'div'; @endphp
<{{ $tag }} @if($href) href="{{ $href }}" @endif class="stat-card stat-{{ $color }}">
    <div class="stat-icon"><i class="fas {{ $icon }}"></i></div>
    <div class="stat-content">
        <div class="stat-label">{{ $label }}</div>
        <div class="stat-value">{{ $value }}</div>
        @if($trend || $sub)
            <div class="stat-meta">
                @if($trend)<span class="stat-trend {{ $trendUp ? 'up' : 'down' }}"><i class="fas fa-arrow-{{ $trendUp ? 'up' : 'down' }}"></i> {{ $trend }}</span>@endif
                @if($sub)<span class="stat-sub">{{ $sub }}</span>@endif
            </div>
        @endif
    </div>
</{{ $tag }}>
<style>
.stat-card{display:flex;align-items:flex-start;gap:16px;padding:20px;background:var(--btp-card-bg);border:1px solid var(--btp-border);border-radius:var(--btp-radius-lg);box-shadow:var(--btp-shadow);text-decoration:none;color:inherit;position:relative;overflow:hidden}
.stat-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--stat-color)}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--btp-shadow-hover);color:inherit}
.stat-icon{width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:var(--btp-radius);background:color-mix(in srgb,var(--stat-color) 12%,transparent);color:var(--stat-color);font-size:1.2rem}
.stat-content{flex:1}
.stat-label{font-size:.75rem;font-weight:600;text-transform:uppercase;color:var(--btp-muted);margin-bottom:4px}
.stat-value{font-size:1.5rem;font-weight:800;color:var(--btp-ink);line-height:1.2}
.stat-meta{display:flex;gap:8px;margin-top:6px;font-size:.75rem}
.stat-trend{display:inline-flex;align-items:center;gap:4px;font-weight:600;padding:2px 8px;border-radius:20px}
.stat-trend.up{background:#d1fae5;color:#065f46}
.stat-trend.down{background:#fee2e2;color:#991b1b}
.stat-sub{color:var(--btp-muted)}
.stat-accent{--stat-color:var(--btp-accent)}.stat-success{--stat-color:var(--btp-success)}.stat-danger{--stat-color:var(--btp-danger)}.stat-warning{--stat-color:var(--btp-warning)}.stat-info{--stat-color:var(--btp-info)}.stat-primary{--stat-color:var(--btp-primary)}
</style>