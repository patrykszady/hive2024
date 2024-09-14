<div>
    {{-- Add todo --}}
    {{-- no submit button needed if an input exists under form wire:submit="add" --}}
    {{-- <form wire:submit="add">
        <div class="flex gap-2 mb-4">
            <input wire:model="draft" type="text" class="grow rounded-full shadow shadow-slate-300 px-5 py-3" placeholder="Add next...">
        </div>
    </form> --}}

    {{-- Todo list --}}
    <x-sortable group="tasks" handler="sort" class="grid gap-3">
        @foreach($this->tasks as $task)
            {{-- group flex items-center justify-between p-1.5 bg-white rounded-full shadow shadow-slate-300 --}}
            {{-- wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task->id}} })" --}}
            <x-sortable.item
                :key="$task->id"
                wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task->id}} })"
                class="cursor-pointer pl-1 border border-solid border-gray-300 h-12 hover:bg-gray-100 font-bold rounded-md text-clip overflow-hidden"
                >
                @can('update', $task)
                    <span
                        class="{{ $task->type == 'Milestone' ? 'text-green-600' : '' }}  {{ $task->type == 'Material' ? 'text-yellow-600' : '' }} {{ $task->type == 'Task' ? 'text-indigo-600' : '' }} {{$task->direction == 'right' ? 'float-right' : ''}}"
                        >
                        {{$task->title}}
                    </span>

                    @if($task->vendor)
                        <br>
                        <span class="text-sm font-medium text-gray-600 {{$task->direction == 'right' ? 'float-right' : ''}}">{{$task->vendor->name, 15}}</span>
                    @elseif($task->user)
                        <br>
                        <span class="text-sm font-medium text-gray-600 {{$task->direction == 'right' ? 'float-right' : ''}}">{{$task->user->first_name, 15}}</span>
                    @endif
                @endcan
            </x-sortable.item>
        @endforeach
    </x-sortable>
</div>
