<x-modal wire:model="showModal">
    <x-modal.panel>
        <form wire:submit="{{$view_text['form_submit']}}">
            {{-- HEADER --}}
            <x-cards.heading>
                <x-slot name="left">
                    <h1>{{$view_text['card_title']}}</h1>
                </x-slot>
            </x-cards.heading>

            {{-- ROWS --}}
            <x-cards.body :class="'space-y-4 my-4'">
                {{-- TYPE --}}
                <x-forms.row
                    wire:model.live="form.type"
                    errorName="form.type"
                    name="type"
                    text="Task Type"
                    type="dropdown"
                    >
                    <option value="Task">Task</option>
                    <option value="Milestone">Milestone</option>
                    <option value="Material">Material</option>
                </x-forms.row>

                {{-- TITLE --}}
                <x-forms.row
                    wire:model.blur="form.title"
                    errorName="form.title"
                    name="title"
                    text="Title"
                    type="text"
                    placeholder="Automatic"
                    autofocus
                    >
                </x-forms.row>

                {{-- DATE --}}
                <x-forms.row
                    wire:model.live.debounce.500ms="form.start_date"
                    errorName="form.start_date"
                    name="start_date"
                    text="Start Date"
                    type="date"
                    >
                </x-forms.row>

                {{-- DURATION --}}
                <x-forms.row
                    wire:model.live="form.duration"
                    errorName="form.duration"
                    name="duration"
                    text="Duration"
                    type="dropdown"
                    >
                    <option value="1">1 Day</option>
                    <option value="2">2 Days</option>
                    <option value="3">3 Days</option>
                    <option value="4">4 Days</option>
                    <option value="5">5 Days</option>
                    <option value="6">6 Days</option>
                    <option value="7">7 Days</option>
                    <option value="8">8 Days</option>
                    <option value="9">9 Days</option>
                    <option value="10">10 Days</option>
                    <option value="11">11 Days</option>
                    <option value="12">12 Days</option>
                    <option value="13">13 Days</option>
                    <option value="14">14 Days</option>
                </x-forms.row>

                {{-- PROJECT --}}
                <x-forms.row
                    wire:model.live="form.project_id"
                    errorName="form.project_id"
                    name="project_id"
                    text="Project"
                    type="dropdown"
                    >
                    <option value="" readonly>Select Project</option>
                    @foreach ($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </x-forms.row>

                {{-- VENDOR --}}
                <x-forms.row
                    wire:model.live="form.vendor_id"
                    errorName="form.vendor_id"
                    name="vendor_id"
                    text="Vendor"
                    type="dropdown"
                    >
                    <option value="" readonly>Select Vendor</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                    @endforeach
                </x-forms.row>

                {{-- USER --}}
                <x-forms.row
                    wire:model.live="form.user_id"
                    errorName="form.user_id"
                    name="user_id"
                    text="Team Member"
                    type="dropdown"
                    >
                    <option value="NULL" readonly>Select Team Member</option>
                    @foreach ($employees as $employee)
                        <option value="{{$employee->id}}">{{$employee->first_name}}</option>
                    @endforeach
                </x-forms.row>

                {{-- NOTES --}}
                <x-forms.row
                    wire:model.blur="form.notes"
                    errorName="form.notes"
                    name="notes"
                    text="Notes"
                    type="textarea"
                    rows="2"
                    placeholder="Notes">
                </x-forms.row>
            </x-cards.body>

            <x-cards.footer>
                <button
                    type="button"
                    x-on:click="open = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                    Cancel
                </button>
                <div
                    {{-- x-data="{ estimate_line_item: @entangle('estimate_line_item') }"
                    x-show="estimate_line_item" --}}
                    >
                    <button
                        {{-- wire:click="removeFromEstimate" --}}
                        type="button"
                        {{-- x-bind:disabled="submit_disabled" --}}
                        x-on:click="open = false"
                        class="px-4 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                        Remove
                    </button>
                </div>
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 ml-3 text-sm text-white bg-indigo-600 border border-transparent rounded-md shadow-sm font-small disabled:opacity-50 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                    {{$view_text['button_text']}}
                </button>
            </x-cards.footer>
        </form>
    </x-modal.panel>
</x-modal>
