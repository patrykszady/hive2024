<div>
    <div class="sticky top-0 z-20 flex-none shadow bg-white overflow-x-scroll" x-bind="scrollSync">
        {{-- PROJECTS FOREACH HERE --}}
        <div class="divide-x divide-gray-100 text-sm leading-6 text-gray-500 grid grid-flow-col auto-cols-max">
            {{-- First. leftmost table column on the first row.  --}}
            <div class="col-end-1 w-14 sticky left-0 z-20 bg-white ring-1 ring-gray-100 shadow"></div>

            @foreach($this->projects as $project_index => $project)
                {{-- items-center justify-center  --}}
                <div class="w-48 p-3 border-b-4">
                    <div class="float-right p-0 m-0">
                        <x-cards.button
                            type="button"
                            {{-- wire:click="$dispatchTo('tasks.planner-card', 'addnew', { project: {{$project->id}} })" --}}
                            wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}} })"
                            button_color="white"
                            >
                            +
                        </x-cards.button>
                    </div>
                    <span class="font-semibold text-gray-800">
                        <a href="{{route('projects.show', $project->id)}}" target="_blank">{{ Str::limit($project->address, 15) }}</a>
                    </span>
                    <br>
                    <span class="font-normal italic text-gray-600">
                        {{ Str::limit($project->project_name, 15) }}
                    </span>
                    <br>
                    {{-- Selected Project (why ... makes sense for the tailwind calendar template we're using .. might want to implenent this on the hirizontal Days /dates div) --}}
                    {{-- <div class="flex items-center justify-center py-3">
                        <span class="flex items-baseline">Wed <span
                                class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">12</span></span>
                    </div> --}}

                    {{-- NO DATE/ NOT SCHEDULE --}}
                    <div class="mt-2">
                        <livewire:tasks.planner-card :$project :task_date="NULL" :key="$project->id" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- HORIZONTAL LINES HERE --}}
    <div class="flex flex-auto overflow-x-auto" x-bind="scrollSync">
        <div class="sticky left-0 z-10 w-14 flex-none ring-1 ring-gray-100 shadow bg-white"></div>

        <div class="divide-y divide-gray-200 -mt-1 pb-4">
            @foreach($this->days as $day_index => $day)
                <div class="sticky left-0 z-10 -ml-14 w-14 pr-2 text-right text-xs text-gray-800 mt-2">
                    <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                    <br>
                    <span class="italic">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                </div>

                <div class="divide-x divide-gray-200 text-sm text-gray-500 grid grid-flow-col auto-cols-max -mt-8 -mt-2">
                    @foreach($this->projects as $project)
                        <div class="w-48 p-3 @if($day['is_today']) bg-white @elseif($day['is_weekend']) bg-gray-200 @endif">
                            <livewire:tasks.planner-card :$project :task_date="$day['database_date']" :key="$project->id" />
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
</div>
