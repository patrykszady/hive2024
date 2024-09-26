@aware([ 'selectable' ])

@props([
    'selectable' => null,
    'key' => null,
])

<tr @if ($key) wire:key="table-{{ $key }}" @endif {{ $attributes }} data-flux-row>
    <?php if ($selectable): ?>
        <flux:cell class="pr-2">
            <flux:checkbox />
        </flux:cell>
    <?php endif; ?>

    {{ $slot }}
</tr>
