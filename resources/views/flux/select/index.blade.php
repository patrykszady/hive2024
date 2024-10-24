@props([
    'multiple' => false,
    'variant' => null,
    'button' => null, // Slot forwarding...
    'search' => null, // Slot forwarding...
    'input' => null, // Slot forwarding...
])

@php
if ($multiple) {
    throw new \Exception('Multiple selects are not supported in this version of Flux...');
}

$variant = $variant === null ? 'default' : $variant;
@endphp

<flux:with-field :$attributes>
    <x-dynamic-component :component="'flux::select.variants.' . $variant" :$attributes :$search :$button :$input>
        {{ $slot }}
    </x-dynamic-component>
</flux:with-field>
