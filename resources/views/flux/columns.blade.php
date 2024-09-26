@aware([ 'selectable' ])

@props([
    'selectable' => null,
])

<thead {{ $attributes }} data-flux-columns>
    <tr {{ isset($tr) ? $tr->attributes : '' }}>
        <?php if ($selectable): ?>
            <flux:column class="pr-2">
                <flux:checkbox />
            </flux:column>
        <?php endif; ?>

        {{ $tr ?? $slot }}
    </tr>
</thead>
