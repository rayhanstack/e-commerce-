@props([
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'card-modern mb-4']) }}>
    @if(isset($header))
        <div class="card-header bg-transparent border-bottom border-light pt-4 pb-3 px-4">
            <h5 class="card-title fw-bold mb-0 text-dark">{{ $header }}</h5>
        </div>
    @endif

    <div class="card-body {{ $noPadding ? 'p-0' : 'p-4' }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="card-footer bg-transparent border-top border-light pb-4 pt-3 px-4">
            {{ $footer }}
        </div>
    @endif
</div>
