@aware([ 'searchable' ])

@props([
    'searchable' => null,
    'search' => null,
])

@php
$classes = Flux::classes()
    ->add('[:where(&)]:min-w-48 [:where(&)]:max-h-[20rem] p-[.3125rem]')
    ->add('rounded-lg shadow-sm')
    ->add('border border-zinc-200 dark:border-zinc-600')
    ->add('bg-white dark:bg-zinc-700')
    ;
@endphp

<?php if (! $searchable): ?>
    <ui-options popover {{ $attributes->class($classes) }} data-flux-options>
        {{ $slot }}
    </ui-options>
<?php else: ?>
    <div popover class="rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-600 bg-white dark:bg-zinc-700 p-[.3125rem]" data-flux-options>
        <?php if ($search): ?> {{ $search }} <?php else: ?>
            <flux:select.search />
        <?php endif; ?>

        <ui-options class="max-h-[20rem] overflow-y-auto -mr-[.3125rem] -mt-[.3125rem] pt-[.3125rem] pr-[.3125rem] -mb-[.3125rem] pb-[.3125rem]">
            {{ $slot }}

            <flux:select.empty>No results</flux:select.empty>
        </ui-options>
    </div>
<?php endif; ?>

