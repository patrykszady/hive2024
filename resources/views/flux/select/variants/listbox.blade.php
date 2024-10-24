@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'placeholder' => null,
    'searchable' => null,
    'invalid' => null,
    'button' => null,
    'search' => null, // Slot forwarding...
    'size' => null,
])

@php
$invalid ??= ($name && $errors->has($name));

$class= Flux::classes()
    ->add('w-full');
@endphp

<ui-select clear="close esc" {{ $attributes->class($class)->merge(['filter' => true]) }} data-flux-control data-flux-select>
    <?php if ($button): ?> {{ $button }} <?php else: ?>
        <flux:select.button :$placeholder :$invalid :$size />
    <?php endif; ?>

    <flux:options :$search>
        {{ $slot}}
    </flux:options>
</ui-select>
