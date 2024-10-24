@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
])

@php
    $classes = Flux::classes()
        ->add('size-[1.125rem] rounded-full mt-px')
        ->add('text-sm text-zinc-700 dark:text-zinc-800')
        ->add('shadow-sm [&[disabled]]:shadow-none data-[checked]:shadow-none indeterminate:shadown-none')
        ->add('flex justify-center items-center [&[data-checked]>div]:block')
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
        ])
        ->add('disabled:opacity-50 ')
        ;
@endphp

<flux:with-inline-field variant="inline" :$attributes>
    {{-- We have to put tabindex="-1" here because otherwise, Livewire requests will wipe out tabindex state, --}}
    {{-- even with durable attributes for some reason... --}}
    <ui-radio {{ $attributes->class($classes) }} data-flux-control data-flux-radio tabindex="-1">
        <div class="hidden size-2 rounded-full bg-white dark:bg-zinc-800"></div>
    </ui-radio>
</flux:with-inline-field>
