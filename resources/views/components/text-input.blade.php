<label for="{{ $name }}" class="form-label fw-bold mb-2">
    {{ $label }}<sup class="text-danger" aria-hidden="true">*</sup>
</label>
<div class="input-group rounded-1">
    @if($icon)
    <span class="input-group-text bg-white text-blue" id="{{ $name }}-icon">
        <i class="bi {{ $icon }}"></i>
    </span>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        class="form-control"
        id="{{ $name }}"
        aria-label="{{ $label }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        aria-describedby="{{ $name }}"
        {{ $attributes->merge([
        'minlength' => $minlength,
        'maxlength' => $maxlength
    ]) }}>

</div>
@error($name)
<small class="text-danger">{{ $message }}</small>
@enderror