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
            <span>{{$days[0]['formatted_date'] . ' - ' . $days[5]['formatted_date']}}</span>
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
                @if(isset($day_tasks[$project->id]))
                    <div wire:sortable-group="taskRearrange" class="grid grid-cols-6 gap-1">
                        @foreach($days as $day)
                            {{-- class="hover:bg-gray-100" --}}
                            <div wire:key="group-{{ $day['database_date'] }}">
                                <h5 class="ml-1">{{ $day['formatted_date'] }}</h5>
                                <ul
                                    wire:sortable-group.item-group="{{ $day['database_date'] }}"
                                    wire:sortable-group.options="{ animation: 100 }"
                                    class="border-t-2 border-gray-100"
                                    >

                                    {{-- @if(isset($day_tasks[$project->id])) --}}
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
                                    {{-- @endif --}}
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-cards.body>
        </x-cards.wrapper>
    @endforeach

    <livewire:tasks.task-create :projects="$projects"/>
</div>
