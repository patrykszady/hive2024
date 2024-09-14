<div class="grid auto-cols-max gap-5">
    {{-- Add todo --}}
    {{-- no submit button needed if an input exists under form wire:submit="add" --}}
    <form wire:submit="add">
        <div class="flex gap-2">
            <input wire:model="draft" type="text" class="grow rounded-full shadow shadow-slate-300 px-5 py-3" placeholder="Add next...">
        </div>
    </form>

    {{-- Todo list --}}
    <div class="grid gap-3">
        @foreach($this->tasks as $task)
            <div class="group p-1.5 bg-white rounded-full shadow shadow-slate-300">
                <div class="px-3 py-1 text-sm text-slate-600">{{$task->title}}</div>
            </div>
        @endforeach
    </div>
</div>
