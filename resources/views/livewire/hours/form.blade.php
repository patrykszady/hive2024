<form wire:submit="{{$view_text['form_submit']}}">
	<div class="grid max-w-xl grid-cols-4 gap-4 xl:relative lg:max-w-5xl sm:px-6">
		{{-- FLOAT CALENDAR --}}
		<div class="col-span-4 space-y-4 lg:col-span-2 lg:h-32">
            <flux:card>
                <flux:heading size="lg">Daily Hours for {{auth()->user()->first_name}}</flux:heading>
                <flux:subheading><i>Pick Date to add or edit Daily Hours for {{auth()->user()->first_name}}</i></flux:subheading>
                <flux:separator variant="subtle" />

                @include('livewire.hours._calander')

                <flux:separator variant="subtle" />

                <div class="space-y-2 mt-2">
                    <flux:button class="w-full"><b>{{$this->selected_date->format('D M jS, Y')}}</b></flux:button>
                    <flux:button class="w-full">Hours | <b>{{$this->hours_count}}</b></flux:button>
                    <flux:button type="submit" variant="primary" class="w-full">{{$view_text['button_text']}}</flux:button>
                </div>

                <flux:error name="check_total_min" />
            </flux:card>
		</div>

		<div class="col-span-4 space-y-2 lg:col-span-2">
            <x-cards accordian="OPENED">
                <x-cards.heading>
                    <x-slot name="left">
                        Projects
                    </x-slot>
				</x-cards.heading>
                <x-cards.body :class="'space-y-2 my-2 divide-gray-200'">
                    {{-- PROJECT HOUR AMOUNT --}}
                    @foreach ($projects as $index => $project)
                        <x-forms.row
                            wire:model.live="form.projects.{{$index}}.hours"
                            errorName="form.projects.{{$index}}.hours"
                            name="form.projects.{{$index}}.hours"
                            label_text_color_custom="{{ !empty($day_project_tasks[$index]) ? 'indigo' : NULL}}"
                            text="{!! '<b>' . $project->address . '</b><br>' . $project->project_name !!}"
                            type="number"
                            hint="Hours"
                            textSize="xl"
                            placeholder="1.00"
                            inputmode="decimal"
                            step="0.25"
                            >

                            <x-slot name="titleslot">
                                <div>
                                    @if(!empty($day_project_tasks[$index]))
                                        @foreach($day_project_tasks[$index] as $task)
                                            <span class="mt-2 text-sm text-indigo-600"><i>{{$task['title']}}</i></span>
                                            <br>
                                            {{-- @if(!$loop->last)
                                                <br>
                                            @endif --}}
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-forms.row>

                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                </x-cards.body>
            </x-cards>

            <flux:card>
                <flux:heading size="lg">Different Project</flux:heading>
                <flux:separator variant="subtle" />
                <flux:input.group>
                    <flux:select wire:model.live="new_project_id">
                        <flux:option value="" readonly>Select Project...</flux:option>

                        @foreach($other_projects as $project)
                            <flux:option value="{{$project->id}}">{{$project->name}}</flux:option>
                        @endforeach

                    </flux:select>

                    <flux:button variant="primary" wire:click="add_project" icon="plus-circle">Add</flux:button>
                </flux:input.group>
            </flux:card>
		</div>
	</div>
</form>
