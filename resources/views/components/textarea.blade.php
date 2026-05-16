<label for="{{ $name }}" class="form-label fw-bold mb-2">
    {{ $label }}
    @if($required)
    <sup class="text-danger" aria-hidden="true">*</sup>
    @endif
</label>

<textarea
    name="{{ $name }}"
    id="{{ $name }}"
    class="form-control @error($name) is-invalid @enderror"
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"
    aria-label="{{ $label }}"
    aria-describedby="{{ $name }}Help"
    >{{ old($name, $value ?? '') }}</textarea>

@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror