@props(['name', 'size' => 20])
<svg {{ $attributes->merge(['width' => $size, 'height' => $size, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('search') <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/> @break
        @case('menu') <path d="M4 6h16M4 12h16M4 18h16"/> @break
        @case('close') <path d="m18 6-12 12M6 6l12 12"/> @break
        @case('heart') <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1.1-1a5.5 5.5 0 0 0-7.8 7.8l1.1 1L12 21l7.8-7.6a5.5 5.5 0 0 0 1-8.8Z"/> @break
        @case('bag') <path d="M6 8h12l1 13H5L6 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/> @break
        @case('user') <circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/> @break
        @case('user-plus')<circle cx="9" cy="8" r="4"/><path d="M2 21v-1a7 7 0 0 1 14 0v1"/><path d="M19 8v6m-3-3h6"/>@break
        @case('arrow') <path d="M5 12h14m-6-6 6 6-6 6"/> @break
        @case('chevron') <path d="m9 18 6-6-6-6"/> @break
        @case('truck') <path d="M10 17h4V5H2v12h3m9-8h4l4 4v4h-3"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="16.5" cy="17.5" r="2.5"/> @break
        @case('shield') <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/> @break
        @case('plus') <path d="M12 5v14M5 12h14"/> @break
        @case('minus') <path d="M5 12h14"/> @break
        @case('star') <path d="m12 2 3 6 7 .9-5 4.8 1.3 6.8L12 17.3l-6.3 3.2L7 13.7 2 9l7-.9 3-6Z"/> @break
        @default <circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
