<div>
	<x-page.top
        class="lg:max-w-4xl"
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
        <x-cards.wrapper class="w-full px-4 {{!$project->no_date_tasks->isEmpty() || !$project->tasks->isEmpty() ? 'pb-8' : ''}} mb-1 sm:px-6 lg:max-w-4xl lg:px-8">
            <x-cards.heading class="px-1 py-1">
                <x-slot name="left">
                    <h1 class="font-bold"><a href="{{route('projects.show', $project->id)}}" target="_blank">{{$project->name}}</a></h1>
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
                @if(!$project->no_date_tasks->isEmpty())
                <div class="noDateTasks overflow-x-auto grid grid-cols-5 gap-1 m-1 p-1">
                    {{-- <div class="col-md-9 bg-red-300">
                        <div class="trash ui-droppable" id="trash">
                        </div>
                    </div>       --}}
                    @foreach($project->no_date_tasks as $task)
                        <div 
                        {{-- border-t-4 {{ $task->type == 'Milestone' ? 'border-green-600' : '' }}  {{ $task->type == 'Material' ? 'border-yellow-600' : '' }} {{ $task->type == 'Task' ? 'border-indigo-600' : '' }} --}}
                            class="grid-stack-item cursor-pointer" 
                            wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task->id}} })" 
                            gs-w="1" gs-h="1" gs-x="1" gs-id="{{$task->id}}"
                            >
                            <div class="pl-1 grid-stack-item-content border border-solid border-gray-300 h-12 hover:bg-gray-100 font-bold rounded-md">
                                <span
                                    class="{{ $task->type == 'Milestone' ? 'text-green-600' : '' }}  {{ $task->type == 'Material' ? 'text-yellow-600' : '' }} {{ $task->type == 'Task' ? 'text-indigo-600' : '' }} {{$task->direction == 'right' ? 'float-right' : ''}}"
                                    >
                                    {{-- {{Str::limit($task->title, 15)}} --}}
                                    {{$task->title}}
                                </span>

                                @if($task->vendor)
                                    <br>
                                    <span class="text-sm font-medium text-gray-600 {{$task->direction == 'right' ? 'float-right' : ''}}">{{$task->vendor->name, 15}}</span>
                                @elseif($task->user)
                                    <br>
                                    <span class="text-sm font-medium text-gray-600 {{$task->direction == 'right' ? 'float-right' : ''}}">{{$task->user->first_name, 15}}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>   
                <hr>   
                @endif              
                
                @if(!$project->no_date_tasks->isEmpty() || !$project->tasks->isEmpty())
                <div class="grid grid-cols-7 gap-1 divide-x divide-solid divide-gray-300">
                    @foreach($days as $day_index => $day)
                        <div class="">
                            <h5
                                wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}}, date: '{{ $day['database_date'] }}' })"
                                class="ml-1 cursor-pointer hover:bg-gray-100"
                                >
                                {{ $day['formatted_date'] }}
                            </h5>
                        </div>
                    @endforeach
                </div>
                <hr>
                @endif

                <div
                    class="grid-stack {{!$project->no_date_tasks->isEmpty() && $project->tasks->isEmpty() ? 'pb-12' : ''}}"
                    x-data="{
                        init() {
                            let grids = GridStack.initAll({
                                column: 7,
                                cellHeight: '60px',
                                cellWidth: '100px',
                                float: false,
                                resizable: {
                                    handles: 'w, e'
                                },
                                margin: 2,
                                acceptWidgets: true,
                                {{-- removable: '.trash', // drag-out delete class --}}
                            });

                            grids[{{$project_index}}].on('added change', function(event, items) {
                                let newItems = [];
                                items.forEach ((el) => {
                                    newItems.push({_id: el._id, x: el.x, y: el.y, w: el.w, task_id: el.id});
                                });

                                $wire.taskMoved(newItems);
                            });
                            GridStack.setupDragIn('.noDateTasks .grid-stack-item', { appendTo: 'body' });
                        }                        
                    }"
                    >
                    {{-- 5/20/2024 if Satruday or Sunday change bg-color --}}
                    {{-- <div class="flex h-full divide-x-2">
                        <div class="bg-transparent" style="width: 71.428571%;"></div>
                        <div class="bg-gray-400" style="width: 28.571429%;"></div>
                    </div> --}}

                    @if(!$project->no_date_tasks->isEmpty() || !$project->tasks->isEmpty())
                    @foreach($days as $day_index => $day)
                        @foreach($project->tasks->where('date', $day['database_date']) as $task)
                            @php
                                $gs_w = $task->direction == 'left' ? (7 - $day_index < $task->duration ? 7 - $day_index : $task->duration) : $day_index + 1;
                                $gs_x = $task->direction == 'left' ? $day_index : 0;
                            @endphp
                            <div
                                class="flex grid-stack-item"
                                gs-id="{{$task->id}}" gs-x="{{$gs_x}}" gs-y="{{$task->order}}" gs-w="{{$gs_w}}"
                                >
                                <div
                                    wire:click="$dispatchTo('tasks.task-create', 'editTask', { task: {{$task->id}} })"
                                    class="p-1 border-{{$task->direction == 'right' ? 'r' : 'l'}}-4 cursor-pointer grid-stack-item-content hover:bg-gray-200 bg-gray-200 bg-opacity-50
                                        {{-- 5/20/2024 if Satruday or Sunday change bg-color --}}
                                        {{-- {{in_array($day_index, [5, 6]) ? 'bg-gray-700' : 'bg-gray-100'}} --}}
                                        

                                        {{-- @if(in_array($day_index, [5, 6]))
                                        bg-gray-700
                                        @else
                                        bg-gray-100
                                        @endif --}}
                                        {{ $task->type == 'Milestone' ? 'border-green-600' : '' }}  {{ $task->type == 'Material' ? 'border-yellow-600' : '' }} {{ $task->type == 'Task' ? 'border-indigo-600' : '' }}
                                    "
                                    >
                           
                                    @if($task->direction == 'left' && 7 - $day_index < $task->duration)
                                        <div class="flex float-right fill-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                            </svg>
                                        </div>
                                    @elseif($task->direction == 'right')
                                        <div class="flex float-left fill-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                            </svg>
                                        </div>
                                    @endif

                                    <span
                                        class="{{ $task->type == 'Milestone' ? 'text-green-600' : '' }}  {{ $task->type == 'Material' ? 'text-yellow-600' : '' }} {{ $task->type == 'Task' ? 'text-indigo-600' : '' }} {{$task->direction == 'right' ? 'float-right' : ''}}"
                                        >
                                        {{-- {{Str::limit($task->title, 15)}} --}}
                                        {{$task->title}}
                                    </span>

                                    @if($task->vendor)
                                        <br>
                                        <span class="text-sm font-medium text-gray-600 {{$task->direction == 'right' ? 'float-right' : ''}}">{{$task->vendor->name, 15}}</span>
                                    @endif

                                    @if($task->user)
                                        <br>
                                        <span class="text-sm font-medium text-gray-600 {{$task->direction == 'right' ? 'float-right' : ''}}">{{$task->user->first_name, 15}}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                    @endif
                </div>
            </x-cards.body>
        </x-cards.wrapper>
    @endforeach

    <livewire:tasks.task-create :projects="$projects" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-all.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-extra.min.css">
</div>
