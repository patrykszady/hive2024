@props([
    'name' => null,
    'logo' => null,
    'href' => '/',
])

@php
$classes = Flux::classes()
    ->add('h-10 flex items-center mr-4')
    ;
@endphp

<?php if ($name): ?>
    <a href="{{ $href }}" {{ $attributes->class([ $classes, 'gap-2' ]) }} data-flux-brand>
        <div class="size-6 rounded overflow-hidden">
            <?php if (is_string($logo)): ?>
                <img src="{{ $logo }}" />
            <?php else: ?>
                {{ $logo ?? $slot }}
            <?php endif; ?>
        </div>

        <div class="text-sm text-zinc-900 dark:text-zinc-100 font-medium truncate">{{ $name }}</div>
    </a>
<?php else: ?>
    <a href="{{ $href }}" {{ $attributes->class($classes) }} data-flux-brand>
        <div class="size-8 rounded overflow-hidden">
            {{ $logo ?? $slot }}
        </div>
    </a>
<?php endif; ?>
