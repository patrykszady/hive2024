@props([
    'expandable' => false,
    'expanded' => true,
    'heading' => null,
])

<?php if ($expandable && $heading): ?>
    <ui-disclosure {{ $attributes->class('group/disclosure') }} @if ($expanded) open @endif data-flux-navlist-group>
        <button type="button" class="w-full h-10 lg:h-8 flex items-center group/disclosure-button mb-[2px] rounded-lg hover:bg-zinc-800/5 hover:dark:bg-white/10 text-zinc-500 hover:text-zinc-800 dark:text-white/80 hover:dark:text-white">
            <div class="pl-3 pr-4">
                <flux:icon.chevron-down class="!size-3 hidden group-data-[open]/disclosure-button:block" />
                <flux:icon.chevron-right class="!size-3 block group-data-[open]/disclosure-button:hidden" />
            </div>

            <span class="text-sm font-medium leading-none">{{ $heading }}</span>
        </button>

        <div class="relative hidden data-[open]:block space-y-[2px] pl-7" @if ($expanded) data-open @endif>
            <div class="absolute inset-y-[3px] w-px bg-zinc-200 dark:bg-white/30 left-0 ml-4"></div>

            {{ $slot }}
        </div>
    </ui-disclosure>
<?php elseif ($heading): ?>
    <div {{ $attributes->class('block space-y-[2px]') }}>
        <div class="px-3 py-2">
            <div class="text-sm text-zinc-400 font-medium leading-none">{{ $heading }}</div>
        </div>

        <div>
            {{ $slot }}
        </div>
    </div>
<?php else: ?>
    <div {{ $attributes->class('block space-y-[2px]') }}>
        {{ $slot }}
    </div>
<?php endif; ?>
