@props([
    'shortcut' => null,
    'name' => null,
])

<div
    {{ $attributes->class('contents') }}
    x-on:click="$dispatch('modal-show', { name: '{{ $name }}' })"
    @if ($shortcut)
        x-on:keydown.{{ $shortcut }}.document="$dispatch('modal-show', { name: '{{ $name }}' })"
    @endif
    data-flux-modal-trigger
>
    {{ $slot }}
</div>
