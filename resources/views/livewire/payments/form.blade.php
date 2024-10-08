<form wire:submit="{{$view_text['form_submit']}}">
    <x-page.top
        h1="{!! $client->name !!} Payment"
        p="Add Payment for Client Projects"
        right_button_href="{{route('clients.show', $client->id)}}"
        right_button_text="Client"
        >
    </x-page.top>

    <div class="grid max-w-xl grid-cols-5 gap-4 mx-auto xl:relative lg:max-w-5xl sm:px-6">
		<div class="col-span-5 space-y-4 lg:col-span-2 lg:h-32 lg:sticky lg:top-5">
			<x-cards>
				<x-cards.heading>
					<x-slot name="left">
						<h1>Client Payment</h1>
						<p class="text-gray-500"><i>Choose Projects to add in this Payment</i></p>
					</x-slot>
				</x-cards.heading>

				<x-cards.body :class="'space-y-2 my-2'">
                    {{-- FORM --}}
                    <x-forms.row
                        wire:model.live.debounce.500ms="form.date"
                        errorName="form.date"
                        name="date"
                        text="Date"
                        type="date"
                        >
                    </x-forms.row>

                    <x-forms.row
                        wire:model.live="form.invoice"
                        errorName="form.invoice"
                        name="invoice"
                        text="Reference"
                        placeholder="Client Check #"
                        type="text"
                        >
                    </x-forms.row>
                    <x-forms.row
                        wire:model.live="form.note"
                        errorName="form.note"
                        name="note"
                        text="Note"
                        type="textarea"
                        rows="1"
                        placeholder="Payment Notes.">
                    </x-forms.row>
				</x-cards.body>

                <x-cards.footer>
                    <div class="w-full space-y-1 text-center">
                        <button
                            type="button"
                            class="w-full px-4 py-2 text-lg font-medium text-center text-gray-900 border-2 border-indigo-600 rounded-md shadow-sm focus:outline-none">

                            Payment Total | <b> {{money($this->client_payment_sum)}}</b>
                        </button>

                        <x-forms.error errorName="payment_total_min" />

                        <button
                            type="submit"
                            class="w-full px-4 py-2 mt-8 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow focus:outline-none hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50">
                            {{$view_text['button_text']}}
                        </button>
                    </div>
                </x-cards.footer>
			</x-car>
		</div>

		<div class="col-span-5 space-y-2 lg:col-span-3">
            {{-- CHOOSE PROJECT DIV --}}
			<x-cards>
                <x-cards.heading>
                    <x-slot name="left">
                        <h1>Choose Payment Projects</h1>
                    </x-slot>
                </x-cards.heading>

                <x-cards.body :class="'space-y-2 my-2'">
                    <x-forms.row
                        wire:model.live.debounce.150ms="form.project_id"
                        errorName="form.project_id"
                        name="project_id"
                        text="Project"
                        type="dropdown"
                        >

                        <option value="" readonly>Select Project</option>
                        @foreach ($projects->where('show', false) as $project)
                            <option value="{{$project->id}}">{{$project->name}}</option>
                        @endforeach
                    </x-forms.row>

                    <x-forms.row
                        wire:click="$dispatch('addProject')"
                        type="button"
                        errorName="project_id_DONT_SHOW"
                        text=""
                        buttonText="Add Project"
                        >
                    </x-forms.row>
                </x-cards.body>
            </x-cards>

            {{-- PAYMENT PROJECTS --}}
            @foreach($projects->where('show', true) as $project_id => $project)
                <x-cards>
                    <x-cards.heading>
                        <x-slot name="left">
                            <h1>{{ $project['name'] }}</h1>
                        </x-slot>

                        <x-slot name="right">
                            {{-- 8/20/2022 x-cards.button --}}
                            <button
                                type="button"
                                wire:click="$dispatch('removeProject', { project_id_to_remove: {{$project->id}} })"
                                x-transition
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                Remove Project
                            </button>
                        </x-slot>
                    </x-cards.heading>

                    {{-- ROWS --}}
                    <x-cards.body :class="'space-y-2 my-2'">
                        {{-- AMOUNT --}}
                        <x-forms.row
                            wire:model.live.debounce.200ms="projects.{{$project_id}}.amount"
                            errorName="projects.{{$project_id}}.amount"
                            name="amount"
                            text="Amount"
                            type="number"
                            hint="$"
                            textSize="xl"
                            placeholder="00.00"
                            inputmode="decimal"
                            {{-- pattern="[0-9]*" --}}
                            step="0.01"
                            autofocus
                            >
                        </x-forms.row>
                    </x-cards.body>
                </x-cards>
            @endforeach
        </div>
    </div>
</form>


