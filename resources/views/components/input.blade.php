@props(['name','label'=>null,'type'=>'text','value'=>null,'placeholder'=>null,'icon'=>null,'required'=>false,'help'=>null,'col'=>'col-12'])
@php $id = $name . '_' . Str::random(4); @endphp
<div class="{{ $col }}">
    @if($label)
        <label for="{{ $id }}" class="form-label-btp">{{ $label }} @if($required)<span class="text-btp-danger">*</span>@endif</label>
    @endif
    <div class="input-btp-group @error($name) is-invalid @enderror">
        @if($icon)<span class="input-btp-icon"><i class="fas {{ $icon }}"></i></span>@endif
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}"
               {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => 'form-control-btp']) }}>
    </div>
    @if($help)<small class="form-help">{{ $help }}</small>@endif
    @error($name)<div class="invalid-feedback-btp"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
</div>
<style>
.form-label-btp{display:block;font-weight:600;font-size:.8rem;color:var(--btp-ink);margin-bottom:6px}
.input-btp-group{display:flex;align-items:center;background:#f8f9fc;border:2px solid var(--btp-border);border-radius:var(--btp-radius);transition:all var(--btp-transition);overflow:hidden}
.input-btp-group:focus-within{border-color:var(--btp-accent);background:#fff;box-shadow:0 0 0 4px rgba(240,144,12,.1)}
.input-btp-group.is-invalid{border-color:var(--btp-danger);background:#fef2f2}
.input-btp-icon{display:flex;align-items:center;justify-content:center;padding:0 14px;color:var(--btp-muted)}
.form-control-btp{flex:1;padding:11px 16px;border:none;background:transparent;font-size:.9rem;color:var(--btp-ink);outline:none;width:100%}
.form-help{display:block;margin-top:4px;font-size:.75rem;color:var(--btp-muted)}
.invalid-feedback-btp{display:flex;align-items:center;gap:6px;margin-top:6px;font-size:.78rem;color:var(--btp-danger);font-weight:500}
</style>