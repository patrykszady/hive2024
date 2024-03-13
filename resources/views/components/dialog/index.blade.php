<div
    x-data="{ open: false }"
    x-modelable="open"
    {{-- ->whereStartsWith('wire:model') --}}
    {{ $attributes }}
    >
    {{ $slot }}
</div>
