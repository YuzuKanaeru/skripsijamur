@props(['active'])

@php
    if ($active ?? false) {
        $style = 'display:block;width:100%;padding:8px 12px;border-left:4px solid #ef4444;background:#f8fafc;color:#111;text-decoration:none;';
    } else {
        $style = 'display:block;width:100%;padding:8px 12px;color:#444;text-decoration:none;';
    }
@endphp

<a {{ $attributes->merge(['style' => $style]) }}>
    {{ $slot }}
</a>
