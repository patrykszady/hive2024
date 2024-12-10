<div>
    {{--  x-bind="scrollSync" --}}
    <div class="sticky top-0 flex-none overflow-x-scroll">
        {{-- PROJECTS FOREACH HERE --}}
        <div class="divide-x divide-white text-sm leading-6 text-gray-500 grid grid-flow-col auto-cols-max">
            {{-- First. leftmost table column on the first row.  --}}
            <div class="col-end-1 w-14 sticky left-0 bg-white ring-1 ring-gray-100 shadow"></div>

            @foreach($projects as $project)
                <div class="w-64 p-4 bg-gray-100">
                    <flux:card class="py-2 pl-2 pr-2 flex justify-between">
                        <div class="space-y-6">
                            <span class="font-semibold text-gray-800">
                                <a href="{{route('projects.show', $project->id)}}" target="_blank">{{ Str::limit($project->address, 18) }}</a>
                            </span>
                            <br>
                            <span class="font-normal italic text-gray-600">
                                {{ Str::limit($project->project_name, 18) }}
                            </span>
                        </div>
                         <flux:button
                            {{-- wire:click="$dispatchTo('planner.planner-card', 'form_modal')"
                            wire:click="form_modal" --}}
                            wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}} })"
                            icon="plus"
                        />
                    </flux:card>

                    @foreach($days as $day)
                        {{$day['database_date']}}
                        <br>
                        @foreach($project->tasks()->where('start_date', $day['database_date'])->get() as $task)
                            <flux:card
                                @class([
                                    'px-2 py-2',
                                    'bg-zinc-200' => $day['is_weekend'] ? true : false
                                ])
                                >
                                {{$task->title}}
                            </flux:card>
                        @endforeach
                    @endforeach

                    {{-- ACCORDIAN HERE --}}
                    {{-- NO DATE/ NOT SCHEDULE --}}
                    {{-- <div class="mt-2">
                        <livewire:planner.planner-card :task_date="NULL" :projects="$this->projects" :$project :key="$project->id" />
                    </div> --}}
                </div>

                {{--  :projects="$this->projects" --}}
                {{--  :project="$project" --}}
                {{-- <livewire:planner.planner-card /> --}}
            @endforeach
        </div>

        <livewire:tasks.task-create :projects="$this->projects" />
    </div>
</div>




{{-- HORIZONTAL LINES HERE --}}
    <div class="flex flex-auto overflow-x-auto" x-bind="scrollSync">
        <div class="sticky left-0 w-14 flex-none ring-1 ring-gray-100 shadow bg-white"></div>

        <div class="divide-x divide-gray-200">
            @foreach($days as $day)
                {{-- {{$day['formatted_date']}} --}}
                <div class="sticky left-0 -ml-14 w-14 pr-2 text-right text-xs text-gray-800">
                    <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                    <br>
                    <span class="italic">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                </div>
                {{-- divide-x divide-gray-200  --}}
                {{-- auto-cols-max --}}
                <div class="text-sm text-gray-500 grid grid-flow-col -mt-8">
                    @foreach($this->projects as $project)
                        <div class="w-64 p-2">
                            <flux:card
                                @class([
                                    'px-2 py-2',
                                    'bg-zinc-200' => $day['is_weekend'] ? true : false
                                ])
                                >
                                <livewire:tasks.planner-card :$project :task_date="$day['database_date']" :key="$project->id" />
                            </flux:card>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.store('scrollSync', {
                scrollLeft: 0,
            })
            Alpine.bind('scrollSync', {
                '@scroll'(){
                    this.$store.scrollSync.scrollLeft = this.$el.scrollLeft
                },
                'x-effect'() {
                    this.$el.scrollLeft = this.$store.scrollSync.scrollLeft
                }
            })
        })
    </script>
    <livewire:tasks.task-create :projects="$this->projects" />






    {{-- HORIZONTAL LINES HERE --}}
    {{--  x-bind="scrollSync" --}}
    <div class="flex flex-auto overflow-x-auto">
        <div class="sticky left-0 w-14 flex-none ring-1 ring-gray-100 shadow bg-white"></div>

        <div class="divide-x divide-gray-200">
            {{-- <div class="mt-2">
                <livewire:planner.planner-card :task_date="NULL" :projects="$this->projects" :$project :key="$project->id" />
            </div> --}}
            @foreach($days as $day)
                {{-- {{$day['formatted_date']}} --}}
                <div class="sticky left-0 -ml-14 w-14 pr-2 text-right text-xs text-gray-800">
                    <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                    <br>
                    <span class="italic">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                </div>
                {{-- divide-x divide-gray-200  --}}
                {{-- auto-cols-max --}}
                <div class="text-sm text-gray-500 grid grid-flow-col -mt-8">
                    @foreach($this->projects as $project)
                        <div class="w-64 p-2">
                            <flux:card
                                @class([
                                    'px-2 py-2',
                                    'bg-zinc-200' => $day['is_weekend'] ? true : false
                                ])
                                >
                                <livewire:planner.planner-card :$project :projects="$this->projects" :task_date="$day['database_date']" :key="$project->id . $day['database_date']" />
                            </flux:card>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>





@foreach($days as $day)
    {{$day['database_date']}}
    <br>
    @foreach($projects as $project)
        @foreach($project->tasks()->where('start_date', $day['database_date'])->get() as $task)
            <flux:card
                @class([
                    'px-2 py-2',
                    'bg-zinc-200' => $day['is_weekend'] ? true : false
                ])
                >
                {{$task->title}}
            </flux:card>
        @endforeach
    @endforeach
@endforeach





@can('update', $task)
@if(!is_null($task->start_date) ? $task->start_date->format('Y-m-d') == $day['database_date'] : true)
    <span
        class="{{ $task->type == 'Milestone' ? 'text-green-600' : '' }}  {{ $task->type == 'Material' ? 'text-yellow-600' : '' }} {{ $task->type == 'Task' ? 'text-indigo-600' : '' }} {{$task->direction == 'right' ? 'float-right' : ''}}"
        >
        {{$task->title}}
    </span>
@else
    <span
        class="{{ $task->type == 'Milestone' ? 'text-green-300' : '' }}  {{ $task->type == 'Material' ? 'text-yellow-300' : '' }} {{ $task->type == 'Task' ? 'text-indigo-300' : '' }} {{$task->direction == 'right' ? 'float-right' : ''}}"
        >
        {{$task->title}}
    </span>
@endif

{{-- @if($task->duration > 1)
    <span class="float-right text-gray-400 mr-2">
        @if($task->start_date->format('Y-m-d') == $day['database_date'])
            <flux:icon.chevron-down variant="mini" />
        @elseif($task->end_date->format('Y-m-d') == $day['database_date'])
            <flux:icon.chevron-up variant="mini" />
        @else
            <flux:icon.chevron-up-down variant="solid" />
        @endif
    </span>
@endif --}}

<br>
<span class="text-sm font-medium @if(!is_null($task->start_date) ? $task->start_date->format('Y-m-d') == $day['database_date'] : true) text-gray-600 @else text-gray-300 @endif">
    @if($task->vendor) {{$task->vendor->name, 15}} @elseif($task->user) {{$task->user->first_name, 15}} @endif
</span>
@endcan
