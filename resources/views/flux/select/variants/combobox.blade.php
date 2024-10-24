@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'placeholder' => null,
    'searchable' => null,
    'invalid' => null,
    'input' => null,
    'size' => null,
])

@php
$invalid ??= ($name && $errors->has($name));

$class= Flux::classes()
    ->add('w-full');
@endphp

<ui-select autocomplete="strict" clear="esc" {{ $attributes->class($class)->merge(['filter' => true]) }} data-flux-control data-flux-select>
    <?php if ($input): ?> {{ $input }} <?php else: ?>
        <flux:select.input :$placeholder :$invalid :$size />
    <?php endif; ?>

    <flux:options>
        {{ $slot}}

        <flux:select.empty>No results</flux:select.empty>
    </flux:options>
</ui-select>
