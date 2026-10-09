@props(['variant'=>'default', 'icon'=>null, 'dot'=>false])
<span {{ $attributes->merge(['class' => "badge-btp badge-{$variant}"]) }}>
    @if($dot)<span class="badge-dot"></span>@endif
    @if($icon)<i class="fas {{ $icon }}"></i>@endif
    {{ $slot }}
</span>
<style>
.badge-btp{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;letter-spacing:.3px;text-transform:uppercase}
.badge-btp i{font-size:.8em}
.badge-dot{width:6px;height:6px;border-radius:50%;background:currentColor}
.badge-default{background:#f0f2f5;color:#4a5568}
.badge-primary{background:rgba(28,37,48,.1);color:var(--btp-primary)}
.badge-accent{background:rgba(240,144,12,.15);color:var(--btp-accent-dark)}
.badge-success{background:#d1fae5;color:#065f46}
.badge-danger{background:#fee2e2;color:#991b1b}
.badge-warning{background:#fef3c7;color:#92400e}
.badge-info{background:#dbeafe;color:#1e40af}
.badge-purple{background:#ede9fe;color:#5b21b6}
</style>