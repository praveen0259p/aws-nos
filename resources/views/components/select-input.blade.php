<label for="{{ $name }}" class="form-label fw-bold mb-2">
    {{ $label }} <sup class="text-danger" aria-hidden="true">*</sup>
</label>
<div class="input-group rounded-1">
    <span class="input-group-text bg-white text-blue" id="category-icon">
        <i class="bi {{ $icon }}"></i>
    </span>
    <select
        class="form-select"
        id="{{ $name }}"
        name="{{ $name }}"
        aria-label="{{ $name }}"
        aria-describedby="{{ $name }}"
    >
        {{-- Placeholder --}}
        <option value="" {{ old($name) ? '' : 'selected' }}>
            {{ $placeholder ?? $label }}
        </option>

        {{-- Options --}}
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>

{{-- Error message --}}
@error($name)
<small class="text-danger">{{ $message }}</small>
@enderror
