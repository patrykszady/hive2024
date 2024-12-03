<div>
    {{--  x-bind="scrollSync" --}}
    <div class="sticky top-0 flex-none overflow-x-scroll">
        {{-- PROJECTS FOREACH HERE --}}
        <div class="divide-x divide-white text-sm leading-6 text-gray-500 grid grid-flow-col auto-cols-max">
            {{-- First. leftmost table column on the first row.  --}}
            <div class="col-end-1 w-14 sticky left-0 bg-white ring-1 ring-gray-100 shadow"></div>

            @foreach($this->projects as $project)
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
                            wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}} })"
                            icon="plus"
                        />
                    </flux:card>

                    {{-- ACCORDIAN HERE --}}
                    {{-- NO DATE/ NOT SCHEDULE --}}
                    <div class="mt-2">
                        <livewire:planner.planner-card :task_date="NULL" :projects="$this->projects" :$project :key="$project->id" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <livewire:tasks.task-create :projects="$this->projects" />
</div>
