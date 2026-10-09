@props(['variant'=>'primary','size'=>'md','icon'=>null,'href'=>null,'type'=>'button'])
@php $tag = $href ? 'a' : 'button'; @endphp
<{{ $tag }} @if($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    {{ $attributes->merge(['class' => "btn-btp btn-{$variant} btn-{$size}"]) }}>
    @if($icon)<i class="fas {{ $icon }}"></i>@endif
    <span>{{ $slot }}</span>
</{{ $tag }}>
<style>
.btn-btp{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:9px 18px;border:1px solid transparent;border-radius:var(--btp-radius);font-weight:600;font-size:.85rem;cursor:pointer;text-decoration:none;transition:all var(--btp-transition);white-space:nowrap}
.btn-btp:active{transform:scale(.97)}
.btn-sm{padding:6px 12px;font-size:.78rem}
.btn-lg{padding:12px 24px;font-size:.95rem}
.btn-primary{background:var(--btp-primary);color:#fff}.btn-primary:hover{background:var(--btp-secondary);color:#fff}
.btn-secondary{background:var(--btp-secondary);color:#fff}.btn-secondary:hover{background:var(--btp-primary);color:#fff}
.btn-accent{background:var(--btp-accent);color:#fff;box-shadow:0 4px 12px rgba(240,144,12,.25)}.btn-accent:hover{background:var(--btp-accent-dark);color:#fff}
.btn-success{background:var(--btp-success);color:#fff}.btn-success:hover{background:#226e48;color:#fff}
.btn-danger{background:var(--btp-danger);color:#fff}.btn-danger:hover{background:#b91c1c;color:#fff}
.btn-warning{background:var(--btp-warning);color:#fff}.btn-warning:hover{background:#92400e;color:#fff}
.btn-info{background:var(--btp-info);color:#fff}.btn-info:hover{background:#1e40af;color:#fff}
.btn-ghost{background:transparent;color:var(--btp-ink)}.btn-ghost:hover{background:var(--btp-border-soft)}
.btn-outline{background:transparent;color:var(--btp-primary);border-color:var(--btp-border)}.btn-outline:hover{background:var(--btp-primary);color:#fff;border-color:var(--btp-primary)}
</style>