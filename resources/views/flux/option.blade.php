@aware([ 'variant' ])

@props([
    'variant' => null,
])

@php
// This prevents variants from non-select components like flux::modal from being used here...
$variant = Flux::componentExists('select.variants.' . $variant)
    ? $variant : null;

$custom = ! in_array($variant, [null, 'default']);

$classes = Flux::classes()
    ->add('group/option overflow-hidden data-[hidden]:hidden group flex items-center px-2 py-1.5 w-full focus:outline-none')
    ->add('rounded-md')
    ->add('text-left text-sm font-medium')
    ->add('text-zinc-800 data-[active]:bg-zinc-100 [&[disabled]]:text-zinc-400 dark:text-white data-[active]:dark:bg-zinc-600 dark:[&[disabled]]:text-zinc-400')
    ->add('scroll-my-[.3125rem]') // This is here so that when a user scrolls to the top or bottom of the list, the padding is included...
    ;
@endphp

<?php if ($custom): ?>
    <ui-option {{ $attributes->class($classes) }} data-flux-option>
        <div class="w-6 shrink-0 [ui-selected_&]:hidden">
            <flux:icon variant="mini" icon="check" class="hidden group-data-[selected]/option:block" />
        </div>

        {{ $slot }}
    </ui-option>
<?php else: ?>
    <option {{ $attributes }}>{{ $slot }}</option>
<?php endif; ?>


