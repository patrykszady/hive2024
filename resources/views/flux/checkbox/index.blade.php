@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
])

@php
$classes = Flux::classes()
    ->add('size-[1.125rem] rounded-[.3rem] mt-px')
    ->add('text-sm text-zinc-700 dark:text-zinc-800')
    ->add('shadow-sm [&[disabled]]:shadow-none data-[checked]:shadow-none data-[indeterminate]:shadow-none')
    ->add('flex justify-center items-center [&[data-checked]:not([data-indeterminate])>svg:first-child]:block [&[data-indeterminate]>svg:last-child]:block')
    ->add([
        'border',
        'border-zinc-300 dark:border-white/10',
        '[&[disabled]]:border-zinc-200  dark:[&[disabled]]:border-white/5',
        'data-[checked]:border-transparent data-[indeterminate]:border-transparent',
        '[&[disabled]]:data-[checked]:border-transparent data-[indeterminate]:border-transparent',
    ])
    ->add([
        'bg-white dark:bg-white/10',
        'dark:[&[disabled]]:bg-white/5',
        'data-[checked]:bg-zinc-800 dark:data-[checked]:bg-white',
        '[&[disabled]]:data-[checked]:bg-zinc-500 dark:[&[disabled]]:data-[checked]:bg-white/60',
        'data-[checked]:hover:bg-zinc-800 dark:data-[checked]:hover:bg-white',
        'data-[checked]:focus:bg-zinc-800 dark:data-[checked]:focus:bg-white',
        'data-[indeterminate]:bg-zinc-800 dark:data-[indeterminate]:bg-white',
        'data-[indeterminate]:hover:bg-zinc-800 dark:data-[indeterminate]:hover:bg-white',
        'data-[indeterminate]:focus:bg-zinc-800 dark:data-[indeterminate]:focus:bg-white',
    ])
    ->add('disabled:opacity-50')
    ;
@endphp

<flux:with-inline-field :$attributes>
    <ui-checkbox {{ $attributes->class($classes) }} data-flux-control data-flux-checkbox>
        <flux:icon.check variant="micro" class="hidden text-white dark:text-zinc-800" />
        <flux:icon.minus variant="micro" class="hidden text-white dark:text-zinc-800" />
    </ui-checkbox>
</flux:with-inline-field>
