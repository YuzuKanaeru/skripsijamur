@props(['active'])

@php
    if ($active ?? false) {
        $style = 'display:inline-block;padding:8px 12px;border-bottom:3px solid #ef4444;color:#111;font-weight:600;text-decoration:none;';
    } else {
        $style = 'display:inline-block;padding:8px 12px;color:#444;text-decoration:none;';
    }
@endphp

<a {{ $attributes->merge(['style' => $style]) }}>
    {{ $slot }}
</a>
