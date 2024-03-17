<!-- Trigger -->
<span x-on:click="open = true">
    <button
        {{$attributes->whereStartsWith('wire:click')}}
        type="button"
        class="px-4 py-2 text-sm text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
        {{ $slot }}
    </button>
</span>
