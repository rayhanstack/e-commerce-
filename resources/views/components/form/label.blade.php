@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'form-label fw-medium']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
