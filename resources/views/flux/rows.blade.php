@php
$classes = Flux::classes()
    ->add('divide-y divide-zinc-800/10 dark:divide-white/20')
    ;
@endphp

<tbody {{ $attributes->class($classes) }} data-flux-rows>
    {{ $slot }}
</tbody>
