{{-- wire:poll --}}
<div>
	<x-page.top
        h1="Project Tasks Timeline"
        p=""
        >
        <x-slot name="right">
            <button
                wire:click="weekToggle('previous')"
                type="button"
                {{-- x-bind:disabled="submit_disabled" --}}
                {{-- x-on:click="open = false" --}}
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                < Previous Week
            </button>
            <br class="hidden:md">
            <span>{{$days[0]['formatted_date'] . ' - ' . $days[5]['formatted_date']}}</span>
            <br class="hidden:md">
            <button
                wire:click="weekToggle('next')"
                type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                Next Week >
            </button>
        </x-slot>
    </x-page.top>

    @foreach($projects as $project)
        <x-cards.wrapper class="max-w-5xl mb-4">
            <x-cards.heading>
                <x-slot name="left">
                    <h1 class="font-medium">{{$project->name}}</h1>
                </x-slot>
                <x-slot name="right">
                    <x-cards.button
                        type="button"
                        wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->name}} })"
                        button_color="white"
                        >
                        Add Task
                    </x-cards.button>
                </x-slot>
            </x-cards.heading>
            <x-cards.body>
                <div wire:sortable-group="taskRearrange" class="grid grid-cols-6 gap-1">
                    @foreach($days as $day_index => $day)
                        {{-- class="hover:bg-gray-100" --}}
                        <div wire:key="group-{{ $day['database_date'] }}">
                            <h5
                                wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}}, day_index: {{$day_index}} })"
                                class="ml-1 border-r cursor-pointer hover:bg-gray-100"
                                >
                                {{ $day['formatted_date'] }}
                            </h5>
                            <ul
                                wire:sortable-group.item-group="{{ $day['database_date'] }}"
                                wire:sortable-group.options="{ animation: 100 }"
                                class="space-y-1 border-t-4 border-gray-100"
                                >

                                @if(isset($day_tasks[$project->id]))
                                    @if(!$day_tasks[$project->id]->where('start_date', $day['database_date'])->isEmpty())
                                        @foreach($day_tasks[$project->id]->where('start_date', $day['database_date']) as $task)
                                            <li
                                                wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task}} })"
                                                wire:sortable-group.item="{{ $task->id }}"
                                                wire:sortable-group.handle
                                                wire:key="task-{{ $task->id }}"
                                                class="p-2 bg-gray-100 border-l-4 border-indigo-600 cursor-pointer hover:bg-gray-200 {{ $task->type == 'Milestone' ? 'border-dashed' : '' }} {{ $task->type == 'Material' ? 'border-double' : '' }}"
                                                >
                                                <span class="text-indigo-600">{{Str::limit($task->title, 15)}}</span>

                                                @if($task->vendor)
                                                    <br>
                                                    <span class="text-sm font-medium text-gray-600">{{Str::limit($task->vendor->name, 15)}}</span>
                                                @endif

                                                @if($task->user)
                                                    <br>
                                                    <span class="text-sm font-medium text-gray-600">{{Str::limit($task->user->first_name, 15)}}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    @endif
                                @endif
                            </ul>
                        </div>
                    @endforeach
                </div>
            </x-cards.body>
        </x-cards.wrapper>
    @endforeach

    <livewire:tasks.task-create :projects="$projects" :days="$days"/>
</div>




                {{-- wire:sortable-group="taskRearrange"  --}}
                <div>
                    @foreach($days as $day_index => $day)
                        {{-- class="hover:bg-gray-100" --}}
                        {{-- wire:key="group-{{ $day['database_date'] }}" --}}
                        <div>
                            <div
                                {{-- wire:sortable-group.item-group="{{ $day['database_date'] }}"
                                wire:sortable-group.options="{ animation: 100 }" --}}
                                class="grid grid-cols-6"
                                >

                                @if(isset($day_tasks[$project->id]))
                                    @if(!$day_tasks[$project->id]->where('start_date', $day['database_date'])->isEmpty())
                                        @foreach($day_tasks[$project->id]->where('start_date', $day['database_date']) as $task)
                                            <div
                                                wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task}} })"
                                                {{-- wire:sortable-group.item="{{ $task->id }}"
                                                wire:sortable-group.handle --}}
                                                wire:key="task-{{ $task->id }}"
                                                {{--  --}}
                                                class="col-start-{{$day_index + 1}} col-span-2 bg-gray-100 border-l-4 border-indigo-600 cursor-pointer hover:bg-gray-200 {{ $task->type == 'Milestone' ? 'border-dashed' : '' }} {{ $task->type == 'Material' ? 'border-double' : '' }}"
                                                >
                                                <span class="text-indigo-600">{{Str::limit($task->title, 15)}}</span>

                                                @if($task->vendor)
                                                    <br>
                                                    <span class="text-sm font-medium text-gray-600">{{Str::limit($task->vendor->name, 15)}}</span>
                                                @endif

                                                @if($task->user)
                                                    <br>
                                                    <span class="text-sm font-medium text-gray-600">{{Str::limit($task->user->first_name, 15)}}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>


                                {{-- wire:sortable-group="taskRearrange"  --}}
                <div>
                    {{-- class="hover:bg-gray-100" --}}
                    {{-- wire:key="group-{{ $day['database_date'] }}" --}}
                    <div>
                        <div
                            {{-- wire:sortable-group.item-group="group-project-1"
                            wire:sortable-group.options="{ animation: 100 }" --}}
                            class="grid grid-cols-6 gap-1"
                            wire:sortable="taskRearrange"
                            >
                            <div
                                wire:sortable.item="1"

                                wire:key="task-1"
                                class="col-span-2 col-start-1 bg-gray-100 border-l-4 border-indigo-600 cursor-pointer hover:bg-gray-200"
                                >
                                <span class="text-indigo-600">Task Test 1</span>
                            </div>
                            <div
                            wire:sortable.item="2"

                            wire:key="task-2"
                                class="col-span-3 col-start-2 row-span-1 row-start-1 bg-gray-100 border-l-4 border-indigo-600 cursor-pointer hover:bg-gray-200"
                                >
                                <span class="text-indigo-600">Task Test 2</span>
                            </div>
                            <div
                            wire:sortable.item="3"

                            wire:key="task-3"
                                class="col-span-1 col-start-4 row-span-1 row-start-2 bg-gray-100 border-l-4 border-indigo-600 cursor-pointer hover:bg-gray-200"
                                >
                                <span class="text-indigo-600">Task Test 3</span>
                            </div>
                            <div
                            wire:sortable.item="4"

                            wire:key="task-4"
                                class="col-span-1 col-start-5 row-span-1 row-start-1 bg-gray-100 border-l-4 border-indigo-600 cursor-pointer hover:bg-gray-200"
                                >
                                <span class="text-indigo-600">Task Test 4</span>
                            </div>

                        </div>
                    </div>
                </div>















{{-- wire:poll --}}
<div>
	<x-page.top
        h1="Project Tasks Timeline"
        p=""
        >
        <x-slot name="right">
            <button
                wire:click="weekToggle('previous')"
                type="button"
                {{-- x-bind:disabled="submit_disabled" --}}
                {{-- x-on:click="open = false" --}}
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                < Previous Week
            </button>
            <br class="hidden:md">
            <span>{{$days[0]['formatted_date'] . ' - ' . $days[5]['formatted_date']}}</span>
            <br class="hidden:md">
            <button
                wire:click="weekToggle('next')"
                type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                Next Week >
            </button>
        </x-slot>
    </x-page.top>

    @foreach($projects as $project)
        <x-cards.wrapper class="max-w-5xl mb-4">
            <x-cards.heading>
                <x-slot name="left">
                    <h1 class="font-medium">{{$project->name}}</h1>
                </x-slot>
                <x-slot name="right">
                    <x-cards.button
                        type="button"
                        wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}} })"
                        button_color="white"
                        >
                        Add Task
                    </x-cards.button>
                </x-slot>
            </x-cards.heading>
            <x-cards.body>
                <div class="grid grid-cols-6">
                    @foreach($days as $day_index => $day)
                        {{-- class="hover:bg-gray-100" --}}
                        <div>
                            <h5
                                {{-- wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}}, day_index: {{$day_index}} })" --}}
                                class="ml-1 border-r cursor-pointer hover:bg-gray-100"
                                >
                                {{ $day['formatted_date'] }}
                            </h5>
                        </div>
                    @endforeach
                </div>
                {{--   --}}
                <div wire:sortable-group="taskRearrange">
                    @foreach($days as $day_index => $day)
                        {{-- class="hover:bg-gray-100" --}}
                        {{--  --}}
                        <div wire:key="group-{{ $day['database_date'] }}">
                            <div
                                wire:sortable-group.item-group="{{ $day['database_date'] }}"
                                wire:sortable-group.options="{ animation: 100 }"
                                class="grid grid-cols-6"
                                >

                                @if(isset($day_tasks[$project->id]))
                                    @if(!$day_tasks[$project->id]->where('start_date', $day['database_date'])->isEmpty())
                                        @foreach($day_tasks[$project->id]->where('start_date', $day['database_date']) as $task)
                                            <div
                                                wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task}} })"
                                                wire:sortable-group.item="{{ $task->id }}"
                                                wire:sortable-group.handle
                                                wire:key="task-{{ $task->id }}"
                                                {{--  --}}
                                                class="col-start-{{$day_index + 1}} col-span-2 bg-gray-100 border-l-4 border-indigo-600 cursor-pointer hover:bg-gray-200 {{ $task->type == 'Milestone' ? 'border-dashed' : '' }} {{ $task->type == 'Material' ? 'border-double' : '' }}"
                                                >
                                                <span class="text-indigo-600">{{Str::limit($task->title, 15)}}</span>

                                                @if($task->vendor)
                                                    <br>
                                                    <span class="text-sm font-medium text-gray-600">{{Str::limit($task->vendor->name, 15)}}</span>
                                                @endif

                                                @if($task->user)
                                                    <br>
                                                    <span class="text-sm font-medium text-gray-600">{{Str::limit($task->user->first_name, 15)}}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-cards.body>
        </x-cards.wrapper>
    @endforeach

    <livewire:tasks.task-create :projects="$projects" :days="$days"/>
</div>







<div
    x-data="{
        init() {
            let grid = GridStack.init({
                column: 6,
                cellHeight: '60px',
                cellWidth: '100px',
                float: false,
                resizable: {
                    handles: 'e'
                },
                margin: 2
            });

            grid.on('change', function(event, items) {

                let newItems = [];

                items.forEach ((el) => {
                    newItems.push({_id: el._id, x: el.x, y: el.y});
                });

                $wire.setItems(newItems);
            });
        }
    }"
>

    <div class="grid-stack" wire:ignore>
        <div class="grid-stack-item">
            <div class="grid-stack-item-content">Item 1</div>
        </div>
        <div class="grid-stack-item" gs-w="2">
            <div class="grid-stack-item-content">Item 2 wider</div>
        </div>
    </div>


    @assets
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-all.js" defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-extra.min.css">
    @endassets
</div>
