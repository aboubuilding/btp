@props(['name','label'=>null,'value'=>null,'rows'=>4,'placeholder'=>null,'required'=>false,'help'=>null,'col'=>'col-12'])
@php $id = $name . '_' . Str::random(4); @endphp
<div class="{{ $col }}">
    @if($label)
        <label for="{{ $id }}" class="form-label-btp">{{ $label }} @if($required)<span class="text-btp-danger">*</span>@endif</label>
    @endif
    <div class="input-btp-group @error($name) is-invalid @enderror" style="align-items:stretch">
        <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
                  {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => 'form-control-btp']) }}>{{ old($name, $value) }}</textarea>
    </div>
    @if($help)<small class="form-help">{{ $help }}</small>@endif
    @error($name)<div class="invalid-feedback-btp"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
</div>