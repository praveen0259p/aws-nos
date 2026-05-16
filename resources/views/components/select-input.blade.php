<label for="{{ $name }}" class="form-label fw-bold mb-2">
    {{ $label }}  @if($required)<sup class="text-danger" aria-hidden="true">*</sup>@endif
</label>
<div class="input-group rounded-1">
    @if($icon)
        <span class="input-group-text text-blue {{ $class ?? 'bg-white' }}" id="category-icon">
            <i class="bi {{ $icon }}"></i>
        </span>
    @endif
    <select
        class="form-select {{$class}}"
        id="{{ $name }}"
        name="{{ $name }}"
        aria-label="{{ $name }}"
        aria-describedby="{{ $name }}">
        {{-- Placeholder --}}
        <option value="" {{ (old($name) !== null || $selected !== '') ? '' : 'selected' }}>
            {{ $placeholder }}
        </option>

        {{-- Options --}}
        @foreach($options as $val => $label)
            <option value="{{ $val }}" 
                {{ (string)old($name, $selected) === (string)$val ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

{{-- Error message --}}
@error($name)
<small class="text-danger">{{ $message }}</small>
@enderror