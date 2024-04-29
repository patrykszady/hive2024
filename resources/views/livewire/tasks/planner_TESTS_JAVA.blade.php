<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('grid', () => ({
            init() {
                var grids = GridStack.initAll({
                    column: 6,
                    cellHeight: '60px',
                    cellWidth: '100px',
                    float: false,
                    resizable: {
                        handles: 'e'
                    },
                    margin: 2
                })

                grids.forEach(el => {
                    el.on('change', function(event, items) {
                        var newItems = [];

                        items.forEach ((el) => {
                            // _id: el._id,
                            newItems.push({x: el.x, y: el.y, w: el.w, task_id: el.id});
                        });

                        // console.log(newItems)
                        @this.taskMoved(newItems);
                    });
                })
            }
        }))
    })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-all.js" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-extra.min.css">

<div x-data="grid">
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
                <div class="grid grid-cols-6 gap-1">
                    @foreach($days as $day_index => $day)
                        <div>
                            <h5
                                wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}}, day_index: {{$day_index}} })"
                                class="ml-1 border-r cursor-pointer hover:bg-gray-100"
                                >
                                {{ $day['formatted_date'] }}
                            </h5>
                        </div>
                    @endforeach
                </div>
                <div class="bg-white grid-stack gs-id-{{$project->id}}" id="{{$project->id}}">
                    @foreach($days as $day_index => $day)
                        @if(isset($day_tasks[$project->id]))
                            @if(!$day_tasks[$project->id]->where('start_date', $day['database_date'])->isEmpty())
                                @foreach($day_tasks[$project->id]->where('start_date', $day['database_date']) as $task)
                                    {{-- {{$day_index < $task->duration ? $task->duration : $day_index - $task->duration}} --}}
                                    <div class="grid-stack-item" gs-x="{{$day_index}}" gs-w="{{6 - $day_index < $task->duration ? 6 - $day_index : $task->duration}}" gs-y="{{$day_index}}" gs-id="{{$task->id}}">
                                        <div
                                            wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task}} })"
                                            class="p-1 bg-gray-100 border-l-4 cursor-pointer grid-stack-item-content hover:bg-gray-200 {{ $task->type == 'Milestone' ? 'border-green-600' : '' }}  {{ $task->type == 'Material' ? 'border-yellow-600' : '' }} {{ $task->type == 'Task' ? 'border-indigo-600' : '' }}"
                                            >
                                            @if(6 - $day_index < $task->duration)
                                                <div class="flex float-right fill-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400 ">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <span class="{{ $task->type == 'Milestone' ? 'text-green-600' : '' }}  {{ $task->type == 'Material' ? 'text-yellow-600' : '' }} {{ $task->type == 'Task' ? 'text-indigo-600' : '' }}">{{Str::limit($task->title, 15)}}</span>
                                            @if($task->vendor)
                                                <br>
                                                <span class="text-sm font-medium text-gray-600">{{Str::limit($task->vendor->name, 15)}}</span>
                                            @endif

                                            @if($task->user)
                                                <br>
                                                <span class="text-sm font-medium text-gray-600">{{Str::limit($task->user->first_name, 15)}}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                </div>
            </x-cards.body>
        </x-cards.wrapper>
    @endforeach

    <livewire:tasks.task-create :projects="$projects" :days="$days"/>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('grid', () => ({
            init() {
                var grids = GridStack.initAll({
                    column: 6,
                    cellHeight: '60px',
                    cellWidth: '100px',
                    float: false,
                    resizable: {
                        handles: 'e'
                    },
                    margin: 2
                })

                grids.forEach(el => {
                    el.on('change', function(event, items) {
                        var newItems = [];

                        items.forEach ((el) => {
                            // _id: el._id,
                            newItems.push({x: el.x, y: el.y, w: el.w, task_id: el.id});
                        });

                        // console.log(newItems)
                        @this.taskMoved(newItems);
                    });
                })
            }
        }))
    })
</script>








@assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-all.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-extra.min.css">
@endassets

<div
    x-data="{
        init() {
            let grids = GridStack.initAll({
                column: 6,
                cellHeight: '60px',
                cellWidth: '100px',
                float: false,
                resizable: {
                    handles: 'e'
                },
                margin: 2
            });

            grids.forEach(element => {
                element.on('change', function(event, items) {
                    let newItems = [];

                    items.forEach ((el) => {
                        newItems.push({_id: el._id, x: el.x, y: el.y, w: el.w, task_id: el.id});
                    });

                    $wire.taskMoved(newItems);
                });
            });
        }
    }"
    >

    @foreach($projects as $project)
        <div class="grid-stack gs-id-{{$project->id}}" id="{{$project->id}}">
            @foreach($days as $day_index => $day)
                @foreach($day->tasks as $task)
                    <div class="grid-stack-item" gs-x="{{$day_index}}" gs-w="{{$task->duration}}" gs-y="1" gs-id="{{$task->id}}">
                        <div
                            wire:click="$dispatchTo('tasks.task-create', 'editTask', { task_id: {{$task->id}} })"
                            class="grid-stack-item-content"
                            >
                            <span>{{$task->title}}</span>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    @endforeach

    <livewire:tasks.task-create :projects="$projects" :days="$days"/>
</div>







<div>
	<x-page.top
        h1="Project Tasks Timeline"
        p=""
        >
        <x-slot name="right">
            <button
                wire:click="weekToggle('previous')"
                type="button"
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
        <x-cards.wrapper class="max-w-5xl mb-4" wire:ignore>
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
                <div class="grid grid-cols-6 gap-1">
                    @foreach($days as $day_index => $day)
                        <div>
                            <h5
                                wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}}, day_index: {{$day_index}} })"
                                class="ml-1 border-r cursor-pointer hover:bg-gray-100"
                                >
                                {{ $day['formatted_date'] }}
                            </h5>
                        </div>
                    @endforeach
                </div>
                {{-- id="{{$project->id}}" --}}
                {{-- wire:key="project-{{$project->id}}"  --}}
                <div class="bg-white grid-stack gs-id-{{$project->id}}">
                    @foreach($days as $day_index => $day)
                        @if(isset($day_tasks[$project->id]))
                            @if(!$day_tasks[$project->id]->where('start_date', $day['database_date'])->isEmpty())
                                @foreach($day_tasks[$project->id]->where('start_date', $day['database_date']) as $task)
                                    {{-- wire:key="task-{{$task->id}}" --}}
                                    <div class="grid-stack-item" gs-x="{{$day_index}}" gs-w="{{6 - $day_index < $task->duration ? 6 - $day_index : $task->duration}}" gs-y="{{$day_index}}" gs-id="{{$task->id}}">
                                        <div
                                            wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task->id}} })"
                                            class="p-1 bg-gray-100 border-l-4 cursor-pointer grid-stack-item-content hover:bg-gray-200 {{ $task->type == 'Milestone' ? 'border-green-600' : '' }}  {{ $task->type == 'Material' ? 'border-yellow-600' : '' }} {{ $task->type == 'Task' ? 'border-indigo-600' : '' }}"
                                            >
                                            @if(6 - $day_index < $task->duration)
                                                <div class="flex float-right fill-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400 ">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <span
                                                class="{{ $task->type == 'Milestone' ? 'text-green-600' : '' }}  {{ $task->type == 'Material' ? 'text-yellow-600' : '' }} {{ $task->type == 'Task' ? 'text-indigo-600' : '' }}"
                                                >
                                                {{Str::limit($task->title, 15)}}
                                            </span>

                                            @if($task->vendor)
                                                <br>
                                                <span class="text-sm font-medium text-gray-600">{{Str::limit($task->vendor->name, 15)}}</span>
                                            @endif

                                            @if($task->user)
                                                <br>
                                                <span class="text-sm font-medium text-gray-600">{{Str::limit($task->user->first_name, 15)}}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                </div>
            </x-cards.body>
        </x-cards.wrapper>
    @endforeach

    <livewire:tasks.task-create :projects="$projects" :days="$days"/>
</div>

@assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-all.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-extra.min.css">
@endassets

@script
    <script>
        let grids = GridStack.initAll({
            column: 6,
            cellHeight: '60px',
            cellWidth: '100px',
            float: false,
            resizable: {
                handles: 'w,e'
            },
            margin: 2
        })
        grids.forEach(grid => {
            grid.on('change', function(event, items) {
                let newItems = []

                // console.log(newItems)

                items.forEach ((el) => {
                    newItems.push({_id: el._id, x: el.x, y: el.y, w: el.w, task_id: el.id})
                });

                $wire.taskMoved(newItems)
            })
        })

        document.addEventListener('taskAdjusted', (day_tasks) => {
            console.log(day_tasks)
        })
    </script>
@endscript
{{-- // console.log(grids) --}}






// document.addEventListener('livewire:initialized', () => {
    //     grids.forEach(grid => {
    //         grid.on('change', function(event, items) {
    //             console.log(items)
    //             items.forEach(function(el) {
    //                 el.push({_id: el._id, x: el.x, y: el.y, w: el.w, task_id: el.id})
    //             })
    //         })
    //     })
    // })




document.addEventListener('taskAdjusted', (day_tasks) => {
    grids.on('added', function(event, items) {
        items.forEach(function(item) {
            newItems.push({_id: el._id, x: el.x, y: el.y, w: el.w, task_id: el.id})
        })
    });
})













<div>
	<x-page.top
        h1="Project Tasks Timeline"
        p=""
        >
        <x-slot name="right">
            <button
                wire:click="weekToggle('previous')"
                type="button"
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
        <x-cards.wrapper class="max-w-5xl mb-4" wire:ignore>
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
                <div class="grid grid-cols-6 gap-1">
                    @foreach($days as $day_index => $day)
                        <div>
                            <h5
                                wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}}, day_index: {{$day_index}} })"
                                class="ml-1 border-r cursor-pointer hover:bg-gray-100"
                                >
                                {{ $day['formatted_date'] }}
                            </h5>
                        </div>
                    @endforeach
                </div>
                {{-- id="{{$project->id}}" --}}

                {{-- gs-id-{{$project->id}}"  --}}
                <div class="bg-white grid-stack" wire:key="project-{{$project->id}}">
                    @foreach($days as $day_index => $day)
                        @if(isset($day_tasks[$project->id]))
                            @if(!$day_tasks[$project->id]->where('start_date', $day['database_date'])->isEmpty())
                                @foreach($day_tasks[$project->id]->where('start_date', $day['database_date']) as $task)
                                    {{--  --}}
                                    <div class="grid-stack-item" wire:key="task-{{$task->id}}" gs-id="{{$task->id}}" gs-x="{{$day_index}}" gs-w="{{6 - $day_index < $task->duration ? 6 - $day_index : $task->duration}}" gs-y="{{$day_index}}">
                                        <div
                                            wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task->id}} })"
                                            class="p-1 bg-gray-100 border-l-4 cursor-pointer grid-stack-item-content hover:bg-gray-200 {{ $task->type == 'Milestone' ? 'border-green-600' : '' }}  {{ $task->type == 'Material' ? 'border-yellow-600' : '' }} {{ $task->type == 'Task' ? 'border-indigo-600' : '' }}"
                                            >
                                            @if(6 - $day_index < $task->duration)
                                                <div class="flex float-right fill-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400 ">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <span
                                                class="{{ $task->type == 'Milestone' ? 'text-green-600' : '' }}  {{ $task->type == 'Material' ? 'text-yellow-600' : '' }} {{ $task->type == 'Task' ? 'text-indigo-600' : '' }}"
                                                >
                                                {{Str::limit($task->title, 15)}}
                                            </span>

                                            @if($task->vendor)
                                                <br>
                                                <span class="text-sm font-medium text-gray-600">{{Str::limit($task->vendor->name, 15)}}</span>
                                            @endif

                                            @if($task->user)
                                                <br>
                                                <span class="text-sm font-medium text-gray-600">{{Str::limit($task->user->first_name, 15)}}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                </div>
            </x-cards.body>
        </x-cards.wrapper>
    @endforeach

    <livewire:tasks.task-create :projects="$projects" :days="$days"/>
</div>

@assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-all.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-extra.min.css">
@endassets

@script
    <script>
        let grids = GridStack.initAll({
            column: 6,
            cellHeight: '60px',
            cellWidth: '100px',
            float: false,
            resizable: {
                handles: 'w,e'
            },
            margin: 2
        })
        grids.forEach(grid => {
            grid.on('change', function(event, items) {
                let newItems = []

                // console.log(newItems)

                items.forEach ((el) => {
                    newItems.push({_id: el._id, x: el.x, y: el.y, w: el.w, task_id: el.id})
                });

                $wire.taskMoved(newItems)
            })
        })
    </script>
@endscript







@foreach($days as $day_index => $day)
@foreach($project->tasks->where('start_date', $day['database_date']) as $task)
{{-- wire:key="task-{{$task->id}}" --}}
    <div class="grid-stack-item" gs-id="{{$task->id}}" gs-x="{{$day_index}}" gs-y="{{$task->order}}" gs-w="{{6 - $day_index < $task->duration ? 6 - $day_index : $task->duration}}">
        <div
            wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task->id}} })"
            class="p-1 bg-gray-100 border-l-4 cursor-pointer grid-stack-item-content hover:bg-gray-200 {{ $task->type == 'Milestone' ? 'border-green-600' : '' }}  {{ $task->type == 'Material' ? 'border-yellow-600' : '' }} {{ $task->type == 'Task' ? 'border-indigo-600' : '' }}"
            >
            @if(6 - $day_index < $task->duration)
                <div class="flex float-right fill-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400 ">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            @endif

            <span
                class="{{ $task->type == 'Milestone' ? 'text-green-600' : '' }}  {{ $task->type == 'Material' ? 'text-yellow-600' : '' }} {{ $task->type == 'Task' ? 'text-indigo-600' : '' }}"
                >
                {{Str::limit($task->title, 15)}}
            </span>

            @if($task->vendor)
                <br>
                <span class="text-sm font-medium text-gray-600">{{Str::limit($task->vendor->name, 15)}}</span>
            @endif

            @if($task->user)
                <br>
                <span class="text-sm font-medium text-gray-600">{{Str::limit($task->user->first_name, 15)}}</span>
            @endif
        </div>
    </div>
@endforeach
@endforeach






















<div>
	<x-page.top
        h1="Project Tasks Timeline"
        p=""
        >
        <x-slot name="right">
            <button
                wire:click="weekToggle('previous')"
                type="button"
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

    @foreach($projects as $project_index => $project)
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
                <div class="grid grid-cols-6 gap-1">
                    @foreach($days as $day_index => $day)
                        <div>
                            <h5
                                wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}}, day_index: {{$day_index}} })"
                                class="ml-1 border-r cursor-pointer hover:bg-gray-100"
                                >
                                {{ $day['formatted_date'] }}
                            </h5>
                        </div>
                    @endforeach
                </div>

                <div
                    class="bg-white grid-stack"
                    x-data="{
                        init() {
                            let grids = GridStack.initAll({
                                column: 6,
                                cellHeight: '60px',
                                cellWidth: '100px',
                                float: false,
                                resizable: {
                                    handles: 'w, e'
                                },
                                margin: 2
                            });

                            grids[{{$project_index}}].on('change', function(event, items) {
                                let newItems = [];

                                items.forEach ((el) => {
                                    newItems.push({_id: el._id, x: el.x, y: el.y, w: el.w, task_id: el.id});
                                });

                                $wire.taskMoved(newItems);
                            });
                        }
                    }"
                    >

                    @foreach($days as $day_index => $day)
                        @foreach($project->tasks->where('start_date', $day['database_date']) as $task)
                            <div
                                class="grid-stack-item"
                                gs-id="{{$task->id}}" gs-x="{{$day_index}}" gs-y="{{$task->order}}" gs-w="{{$task->duration}}"
                                >
                                <div
                                    wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task->id}} })"
                                    class="p-1 bg-gray-100 border-l-4 cursor-pointer grid-stack-item-content hover:bg-gray-200
                                        {{ $task->type == 'Milestone' ? 'border-green-600' : '' }}  {{ $task->type == 'Material' ? 'border-yellow-600' : '' }} {{ $task->type == 'Task' ? 'border-indigo-600' : '' }}"
                                    >
                                    {{$task->title}}
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </x-cards.body>
        </x-cards.wrapper>
    @endforeach

    <livewire:tasks.task-create :projects="$projects" :days="$days"/>
</div>
