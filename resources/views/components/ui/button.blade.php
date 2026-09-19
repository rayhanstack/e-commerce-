@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'block' => false,
])

@php
    $classes = 'btn btn-' . $variant;
    
    if ($size !== 'md') {
        $classes .= ' btn-' . $size;
    }
    
    if ($block) {
        $classes .= ' w-100';
    }
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($disabled) disabled @endif>
    {{ $slot }}
</button>
