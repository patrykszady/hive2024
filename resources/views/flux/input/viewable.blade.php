@php
$attributes = $attributes->merge([
    'variant' => 'subtle',
    'class' => '-mr-1',
    'square' => true,
    'size' => null,
]);
@endphp

<flux:button
    :$attributes
    :size="$size === 'sm' ? 'xs' : 'sm'"
    x-data="{ open: false }"
    x-on:click="open = ! open; $el.closest('[data-flux-input]').querySelector('input').setAttribute('type', open ? 'text' : 'password')"
    x-bind:data-viewable-open="open"
>
    <flux:icon.eye-slash variant="micro" class="hidden [[data-viewable-open]>&]:block" />
    <flux:icon.eye variant="micro" class="block [[data-viewable-open]>&]:hidden" />
</flux:button>