<div>
    <div class="sticky top-0 z-30 flex-none shadow bg-white overflow-x-scroll" x-bind="scrollSync">
        {{-- PROJECTS FOREACH HERE --}}
        <div class="divide-x divide-gray-100 text-sm leading-6 text-gray-500 grid grid-flow-col auto-cols-max">
            {{-- First. leftmost table column on the first row.  --}}
            <div class="col-end-1 w-14 z-10"></div>

            @foreach($projects as $project_index => $project)
                {{-- items-center justify-center  --}}
                <div class="w-48 p-3 border-b-4">
                    <span class="font-semibold text-gray-800">
                        {{ Str::limit($project->address, 22) }}
                    </span>
                    <br>
                    <span class="font-normal italic text-gray-600">
                        {{ Str::limit($project->project_name, 22) }}
                    </span>
                    <br>

                    <x-cards.button
                        type="button"
                        wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}} })"
                        button_color="white"
                        >
                        Add Task
                    </x-cards.button>
                </div>
                {{-- Selected Project (why ... makes sense for the tailwind calendar template we're using .. might want to implenent this on the hirizontal Days /dates div) --}}
                {{-- <div class="flex items-center justify-center py-3">
                    <span class="flex items-baseline">Wed <span
                            class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">12</span></span>
                </div> --}}
            @endforeach
        </div>
    </div>

    {{-- HORIZONTAL LINES HERE --}}
    <div class="flex flex-auto overflow-x-auto" x-bind="scrollSync">
        <div class="sticky left-0 z-20 w-14 flex-none bg-white ring-1 ring-gray-100"></div>

        <div class="divide-y divide-gray-200">
            @foreach($days as $day_index => $day)
                <div class="sticky left-0 z-20 -ml-14 w-14 pr-2 text-right text-xs leading-5 text-gray-800">
                    <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                    <br>
                    <span class="italics">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                </div>

                <div class="divide-x divide-black text-sm text-gray-500 grid grid-flow-col auto-cols-max">
                    @foreach($projects as $project)
                        <div class="w-48 p-3 border-r-4">
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

    <livewire:tasks.task-create :projects="$projects" />
</div>
