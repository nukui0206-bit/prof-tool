@props(['active'])

@php
$classes = 'nav-link' . (($active ?? false) ? ' active fw-semibold' : '');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
