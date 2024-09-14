@props(['handler'])

<div
    {{$attributes}}
    x-sort="$wire.{{ $handler }}($key, $position)"
    >
    {{$slot}}
</div>
