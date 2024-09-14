<div class="grid auto-cols-max gap-5">
    {{-- Add todo --}}
    {{-- no submit button needed if an input exists under form wire:submit="add" --}}
    <form wire:submit="add">
        <div class="flex gap-2">
            <input wire:model="draft" type="text" class="grow rounded-full shadow shadow-slate-300 px-5 py-3" placeholder="Add next...">
        </div>
    </form>

    {{-- Todo list --}}
    <x-sortable handler="sort" class="grid gap-3 min-w-[20rem]">
        @foreach($this->tasks as $task)
            <x-sortable.item :key="$task->id" class="group flex items-center justify-between p-1.5 bg-white rounded-full shadow shadow-slate-300">
                <div class="px-3 py-1 flex gap-2 items-center">
                    <x-sortable.handle class="transition translate-x-[-1.5rem] [body:not(.sorting)_&]:group-hover:translate-x-0 opacity-0 [body:not(.sorting)_&]:group-hover:opacity-100 text-slate-300 cursor-pointer" >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M2 3.75A.75.75 0 0 1 2.75 3h10.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 3.75ZM2 8a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 8Zm0 4.25a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        </svg>
                    </x-sortable.handle>

                    <div class="transition translate-x-[-1.5rem] [body:not(.sorting)_&]:group-hover:translate-x-0 text-sm text-slate-600">{{ $task->title }}</div>
                </div>

                <button wire:click="remove({{$task->id}})" type="button" class="transition opacity-0 [body:not(.sorting)_&]:group-hover:opacity-100 text-slate-500 hover:bg-emerald-100/75 hover:text-emerald-700 rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-sortable.item>
        @endforeach
    </x-sortable>
</div>
