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
                {{-- <x-forms.row
                    wire:model.live.debounce.500ms="form.start_date"
                    errorName="form.start_date"
                    name="start_date"
                    text="Dates"
                    type="date"
                    >
                </x-forms.row> --}}

                {{-- DATES --}}
                <div
                    wire.model.live="form.dates"
                    x-data="{
                        value: @entangle('form.dates'),
                        init() {
                            let picker = flatpickr(this.$refs.picker, {
                                mode: 'range',
                                dateFormat: 'm/d/Y',
                                {{-- minRange: 3,
                                maxRange: 14, // Maximum 10 days in the range --}}
                                defaultDate: this.value,
                                locale: {
                                    firstDayOfWeek: 1, // 0 for Sunday, 1 for Monday, etc.
                                },
                                {{-- disable: [
                                    function (date) {
                                       return (date.getDay() === 6 || date.getDay() === 0);
                                    }
                                ], --}}
                                onChange: (date, dateString) => {
                                    this.value = dateString.split(' to ')
                                    {{-- console.log(this.value) --}}
                                    $wire.dateschanged(this.value)
                                }
                            })

                            this.$watch('value', () => picker.setDate(this.value))
                        },
                    }"
                    >

                    <x-forms.row
                        errorName="form.dates"
                        name="dates"
                        text="Dates"
                        x-ref="picker"
                        >
                    </x-forms.row>
                </div>

                {{-- SAT/SUN INCLUSION --}}
                <x-forms.row
                    wire:model.live="include_weekend_days"
                    errorName="include_weekend_days"
                    name="include_weekend_days"
                    text="Weekend Days"
                    type="checkbox_group"
                    >

                    <div class="space-y-5">
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input
                                    wire:model.live="form.include_weekend_days.saturday"
                                    value="true"
                                    id="form.include_weekend_days.saturday"
                                    name="form.include_weekend_days.saturday"
                                    type="checkbox"
                                    aria-describedby="form.include_weekend_days-description"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                    >
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label
                                    for="form.include_weekend_days.saturday"
                                    class="font-medium text-gray-900"
                                    >
                                    Include Saturday
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input
                                    wire:model.live="form.include_weekend_days.sunday"
                                    value="true"
                                    id="form.include_weekend_days.sunday"
                                    name="form.include_weekend_days.sunday"
                                    type="checkbox"
                                    aria-describedby="form.include_weekend_days-description"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                    >
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label
                                    for="form.include_weekend_days.sunday"
                                    class="font-medium text-gray-900"
                                    >
                                    Include Sunday
                                </label>
                            </div>
                        </div>
                    </div>

                </x-forms.row>

                {{-- DURATION --}}
                <x-forms.row
                    wire:model.live="form.duration"
                    errorName="form.duration"
                    name="duration"
                    text="Duration"
                    type="dropdown"
                    disabled
                    >
                    <option value="0">Not Scheduled</option>
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
                    <option value="" readonly>Select Team Member</option>
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
                        wire:click="removeTask"
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

