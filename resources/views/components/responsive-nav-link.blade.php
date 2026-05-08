@props(['active'])

@php
$classes = 'nav-link d-block' . (($active ?? false) ? ' active fw-semibold' : '');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
