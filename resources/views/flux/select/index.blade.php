@props([
    'variant' => 'default',
    'multiple' => false,
    'search' => null, // Slot forwarding...
])

@php
if ($multiple) {
    throw new \Exception('Multiple selects are not supported in this version of Flux...');
}
@endphp

<flux:with-field :$attributes>
    <x-dynamic-component :component="'flux::select.variants.' . $variant" :$attributes :$search>
        {{ $slot }}
    </x-dynamic-component>
</flux:with-field>
