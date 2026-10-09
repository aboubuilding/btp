@props(['name','label'=>null,'options'=>[],'value'=>null,'placeholder'=>'— Sélectionner —','required'=>false,'icon'=>null,'help'=>null,'col'=>'col-12','select2'=>true])
@php $id = $name . '_' . Str::random(4); @endphp
<div class="{{ $col }}">
    @if($label)
        <label for="{{ $id }}" class="form-label-btp">{{ $label }} @if($required)<span class="text-btp-danger">*</span>@endif</label>
    @endif
    <div class="input-btp-group @error($name) is-invalid @enderror">
        @if($icon)<span class="input-btp-icon"><i class="fas {{ $icon }}"></i></span>@endif
        <select name="{{ $name }}" id="{{ $id }}" {{ $required ? 'required' : '' }}
                {{ $attributes->merge(['class' => 'form-control-btp' . ($select2 ? ' use-select2' : '')]) }}>
            @if($placeholder)<option value="">{{ $placeholder }}</option>@endif
            @foreach($options as $k => $t)<option value="{{ $k }}" @selected(old($name, $value) == $k)>{{ $t }}</option>@endforeach
        </select>
    </div>
    @if($help)<small class="form-help">{{ $help }}</small>@endif
    @error($name)<div class="invalid-feedback-btp"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
</div>