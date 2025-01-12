<div>
    <div class="sticky top-0 flex-none overflow-x-scroll z-10 bg-gray-200" x-bind="scrollSync">
        <div class="divide-x divide-white text-sm leading-6 text-gray-500 grid grid-flow-col auto-cols-max">
            {{-- First. leftmost table column on the first row.  --}}
            <div class="col-end-1 w-14 sticky left-0 bg-white ring-1 ring-gray-100 shadow"></div>

            @foreach($projects as $project)
                <div class="w-64 p-4 bg-gray-200">
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
                            wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}} })"
                            icon="plus"
                        />
                    </flux:card>
                </div>
            @endforeach
        </div>

        {{-- NO DATE/ NOT SCHEDULE --}}
        {{-- ACCORDIAN HERE --}}
        <div class="flex flex-auto bg-gray-200 w-full">
            <div class="sticky left-0 w-14 flex-none ring-1 ring-gray-100 shadow bg-white"></div>

            <div class="divide-x divide-gray-200">
                <div
                    @class([
                        'sticky left-0 -ml-14 w-14 pr-2 text-right text-xs text-gray-800'
                    ])
                    >
                    <span class="font-semibold">NO</span>
                    <br>
                    <span class="italic">DATE</span>
                </div>
            </div>

            <div class="text-sm text-gray-500 grid grid-flow-col">
                @foreach($days as $day_index => $day)
                    @if($day_index === 0)
                        @foreach($this->projects as $project)
                            <div class="w-64 p-2">
                                <flux:card
                                    x-sort="$wire.sort($key, $position, {{$project->id}}, {{$day_index}})"
                                    x-sort:group="tasks"
                                    x-sort:config="{ filter: '.filtered' }"
                                    @class([
                                        'px-2 py-2',
                                        'bg-zinc-200',
                                        'space-y-2'
                                    ])
                                    >
                                    @foreach($project->tasks()->whereNull('start_date')->whereNull('end_date')->get() as $task)
                                        @include('livewire.planner._task_card')
                                        {{-- @if(is_null($task->start_date) && $day['database_date'] === NULL)
                                            @include('livewire.planner._task_card')
                                        @endif --}}
                                    @endforeach
                                </flux:card>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- HORIZONTAL DATE LINES HERE --}}
    <div class="flex flex-auto overflow-x-auto" x-bind="scrollSync">
        <div class="sticky left-0 w-14 flex-none ring-1 ring-gray-100 shadow bg-white"></div>

        <div class="divide-x divide-gray-200">
            @foreach($days as $day_index => $day)
                @if($day['database_date'] !== NULL)
                    <div
                        @class([
                            'sticky left-0 -ml-14 w-14 pr-2 text-right text-xs text-gray-800',
                            '!text-sky-600' => $day['is_today'] ? true : false
                        ])
                        >
                        <span class="font-semibold">{{strtok($day['formatted_date'], ',')}}</span>
                        <br>
                        <span
                            @class([
                                'italic'
                            ])
                            >
                            {{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}
                        </span>
                    </div>

                    <div class="text-sm text-gray-500 grid grid-flow-col -mt-8">
                        @foreach($this->projects as $project)
                            <div class="w-64 p-2">
                                <flux:card
                                    x-sort="$wire.sort($key, $position, {{$project->id}}, {{$day_index}})"
                                    x-sort:group="tasks"
                                    x-sort:config="{ filter: '.filtered' }"
                                    @class([
                                        'px-2 py-2',
                                        'bg-zinc-200' => $day['is_weekend'] OR $day['database_date'] === NULL ? true : false,
                                        '!bg-sky-100' => $day['is_today'] ? true : false,
                                        'space-y-2'
                                    ])
                                    >
                                    @foreach($project->tasks()->get() as $task)
                                        @if(\Carbon\Carbon::parse($day['database_date'])->between($task->start_date, $task->end_date) && $day['database_date'] !== NULL)
                                            @if(isset($task->options->include_weekend_days) && $day['is_weekend'])
                                                @if(isset($task->options->include_weekend_days->saturday) && $task->options->include_weekend_days->saturday === true && $day['is_saturday'] === true)
                                                    @include('livewire.planner._task_card')
                                                @elseif(isset($task->options->include_weekend_days->sunday) && $task->options->include_weekend_days->sunday === true && $day['is_sunday'] === true)
                                                    @include('livewire.planner._task_card')
                                                @endif
                                            @else
                                                @include('livewire.planner._task_card')
                                            @endif
                                        @endif
                                    @endforeach
                                </flux:card>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <livewire:tasks.task-create :employees="$employees" :projects="$projects" :vendors="$vendors" />

    {{-- scrollSync script --}}
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
</div>


