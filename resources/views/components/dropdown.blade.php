@props(['align' => 'right', 'width' => null, 'contentClasses' => null])

@php
$alignClass = match ($align) {
    'left' => 'dropdown-menu-start',
    default => 'dropdown-menu-end',
};
@endphp

<div class="dropdown">
    <div data-bs-toggle="dropdown" aria-expanded="false" role="button">
        {{ $trigger }}
    </div>

    <div class="dropdown-menu {{ $alignClass }} shadow-sm">
        {{ $content }}
    </div>
</div>
