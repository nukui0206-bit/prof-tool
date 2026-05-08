@props([
    'name',
    'show' => false,
    'maxWidth' => 'lg',
])

@php
$sizeClass = match ($maxWidth) {
    'sm' => 'modal-sm',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-xl',
    default => '',
};
@endphp

<div class="modal fade" id="modal-{{ $name }}" tabindex="-1" aria-hidden="true" {{ $show ? 'data-bs-show="true"' : '' }}>
    <div class="modal-dialog {{ $sizeClass }} modal-dialog-centered">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
