@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'placeholder' => null,
    'searchable' => null,
    'invalid' => null,
])

@php
$invalid ??= ($name && $errors->has($name));

[ $styleAttributes, $attributes ] = Flux::splitAttributes($attributes);
@endphp

<ui-select autocomplete="strict" clear="esc" {{ $attributes->merge(['filter' => true]) }} data-flux-control data-flux-select>
    <flux:select.input :attributes="$styleAttributes" :$placeholder :$invalid />

    <flux:options>
        {{ $slot}}

        <flux:select.empty>No results</flux:select.empty>
    </flux:options>
</ui-select>
