<form wire:submit="{{$view_text['form_submit']}}">
	<x-page.top
		h1="Daily Hours for {{auth()->user()->first_name}}"
		p="Daily Hours for {{auth()->user()->first_name}}"
		right_button_href="{{route('timesheets.index')}}"
		right_button_text="Show Timesheets"
		>
	</x-page.top>

	<div class="grid max-w-xl grid-cols-4 gap-4 mx-auto xl:relative lg:max-w-5xl sm:px-6">
		{{-- FLOAT CALENDAR --}}
		<div class="col-span-4 space-y-4 lg:col-span-2 lg:h-32 lg:sticky lg:top-5">
			<x-cards.wrapper>
				<x-cards.heading>
					<x-slot name="left">
						<h1>Daily Hours</h1>
						<p><i>Pick Date to add or edit Daily Hours for {{auth()->user()->first_name}}</i></p>
					</x-slot>
				</x-cards.heading>

                {{-- CALANDER --}}
                {{--  wire:target="save, edit" --}}
				<x-cards.body wire:loading.class="opacity-50 text-opacity-40 pointer-events-none">
					@include('livewire.hours._calander')
				</x-cards.body>

                <x-cards.footer>
					<div class="w-full space-y-1 text-center">
						<button
							class="w-full px-4 py-2 text-lg font-medium text-center text-gray-900 border-2 border-indigo-600 rounded-md shadow-sm focus:outline-none pointer-events-none">
							{{$this->selected_date->format('D M jS, Y')}}
						</button>

                        <button
                            class="w-full px-4 py-2 text-lg font-medium text-center text-gray-900 border-2 border-indigo-600 rounded-md shadow-sm focus:outline-none pointer-events-none">
                            Hours | <b>{{$this->hours_count}}</b>
                        </button>

                        <x-forms.error errorName="hours_count"/>

                        <button
                            type="submit"
                            class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow focus:outline-none hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50">
                            {{$view_text['button_text']}}
                        </button>
					</div>
                </x-cards.footer>
			</x-cards.wrapper>
		</div>

		<div class="col-span-4 space-y-2 lg:col-span-2">
            <x-cards.wrapper>
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
            </x-cards.wrapper>

            <x-cards.wrapper>
                <x-cards.body :class="'space-y-2 my-2'">
                    <x-forms.row
                        {{-- 5-26-2024 disable if $new_project_id is NULL --}}
                        {{-- x-bind:disabled="save_form == 'save' || expense_transactions" --}}
                        wire:model.live="new_project_id"
                        errorName="select_new_project"
                        name="new_project_id"
                        text="Another Project"
                        type="dropdown"
                        buttonHint="Add"
                        buttonClick="add_project"
                        >

                        <option value="" readonly>Select Project</option>
                        @foreach ($other_projects as $index => $project)
                            <option value="{{$project->id}}">{{$project->name}}</option>
                        @endforeach
                    </x-forms.row>
                </x-cards.body>
            </x-cards.wrapper>
		</div>
	</div>
</form>
