@props(['name','label'=>null,'value'=>1,'checked'=>false,'col'=>'col-12'])
@php $id = $name . '_' . Str::random(4); @endphp
<div class="{{ $col }}">
    <label for="{{ $id }}" style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:.88rem">
        <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
               @checked(old($name, $checked)) {{ $attributes }}>
        <span>{{ $label }}</span>
    </label>
</div>