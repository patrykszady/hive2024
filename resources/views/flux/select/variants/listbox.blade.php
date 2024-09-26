@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'placeholder' => null,
    'searchable' => null,
    'invalid' => null,
    'search' => null, // Slot forwarding...
])

@php
$invalid ??= ($name && $errors->has($name));

[ $styleAttributes, $attributes ] = Flux::splitAttributes($attributes);
@endphp

<ui-select clear="close esc" {{ $attributes->merge(['filter' => true]) }} data-flux-control data-flux-select>
    <flux:select.button :attributes="$styleAttributes" :$placeholder :$invalid />

    <flux:options :$search>
        {{ $slot}}
    </flux:options>
</ui-select>
