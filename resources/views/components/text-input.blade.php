<label for="{{ $name }}" class="form-label fw-bold mb-2">
    {{ $label }} 
    @if($required)
        <sup class="text-danger" aria-hidden="true">*</sup>
    @endif
</label>
<div class="input-group rounded-1">
    @if($icon)
    <span class="input-group-text text-blue {{ $class ?? 'bg-white' }}" id="{{ $name }}-icon">
        <i class="bi {{ $icon }}"></i>
    </span>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        class="form-control {{$class}}"
        id="{{ $name }}"
        aria-label="{{ $label }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        aria-describedby="{{ $name }}"
        @if($accept) accept="{{ $accept }}" @endif
        {{ $attributes->merge([
        'minlength' => $minlength,
        'maxlength' => $maxlength
    ]) }}>
    
</div>
@error($name)
<small class="text-danger">{{ $message }}</small>
@enderror