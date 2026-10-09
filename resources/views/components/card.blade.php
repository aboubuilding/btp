@props(['title'=>null, 'icon'=>null, 'padding'=>'normal'])
<div {{ $attributes->merge(['class' => 'card-btp']) }}>
    @if($title)
        <div class="card-btp-header">
            <div class="d-flex align-items-center gap-2">
                @if($icon)<i class="fas {{ $icon }} text-btp-accent"></i>@endif
                <h5 class="mb-0 fw-700">{{ $title }}</h5>
            </div>
            @isset($actions)<div class="d-flex gap-2">{!! $actions !!}</div>@endisset
        </div>
    @endif
    <div class="card-btp-body card-pad-{{ $padding }}">{{ $slot }}</div>
    @isset($footer)<div class="card-btp-footer">{{ $footer }}</div>@endisset
</div>
<style>
.card-btp{background:var(--btp-card-bg);border:1px solid var(--btp-border);border-radius:var(--btp-radius-lg);box-shadow:var(--btp-shadow);transition:box-shadow var(--btp-transition);overflow:hidden}
.card-btp:hover{box-shadow:var(--btp-shadow-hover)}
.card-btp-header{display:flex;align-items:center;justify-content:space-between;padding:16px 22px;border-bottom:1px solid var(--btp-border-soft)}
.card-btp-body{padding:22px}
.card-pad-compact{padding:12px 16px}
.card-pad-large{padding:32px}
.card-btp-footer{padding:14px 22px;border-top:1px solid var(--btp-border-soft);background:#fafbfc}
.dark-theme .card-btp-footer{background:#0d1117}
</style>