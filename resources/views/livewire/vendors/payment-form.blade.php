<div>
    <form wire:submit="{{$view_text['form_submit']}}">
        <x-page.top
            h1="{{$vendor->name}} Payment"
            p="Vendor Payment for {{$vendor->business_name}}"
            {{-- right_button_href="{{route('vendors.show', $vendor->id)}}"
            right_button_text="Vendor" --}}
            >
        </x-page.top>

        <div class="grid max-w-xl grid-cols-5 gap-4 mx-auto xl:relative lg:max-w-5xl sm:px-6">
            <div class="col-span-5 space-y-4 lg:col-span-2 lg:h-32 lg:sticky lg:top-5">
                <x-cards>
                    <x-cards.heading>
                        <x-slot name="left">
                            <h1>Vendor Payment</h1>
                            <p class="text-gray-500"><i>Choose Projects to add for {{$vendor->name}} in this Payment</i></p>
                        </x-slot>
                    </x-cards.heading>

                    <x-cards.body :class="'space-y-2 my-2'">
                        {{-- FORM --}}
                        @include('livewire.checks._payment_form')
                    </x-cards.body>

                    <x-cards.footer>
                        <div class="w-full space-y-1 text-center">
                            <button
                                type="button"
                                class="w-full px-4 py-2 font-medium text-center border-2 rounded-md shadow-sm focus:outline-none text-md cursor-text
                                    @error('check_total_min') text-red-900 border-red-600 @else text-gray-900 border-indigo-600 @enderror
                                ">
                                Check Total | <b>{{money($this->vendor_check_sum)}}</b>
                            </button>

                            <x-forms.error errorName="check_total_min" />
                            <button
                                type="submit"
                                class="w-full px-4 py-2 text-sm text-white bg-indigo-600 border border-transparent rounded-md shadow focus:outline-none hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                {{$view_text['button_text']}}
                            </button>
                        </div>
                    </x-cards.footer>
                </x-cards>

                {{-- INSURANCE --}}
                <livewire:vendor-docs.vendor-docs-card :vendor="$vendor" :view="true"/>
            </div>
            <div class="col-span-5 space-y-2 lg:col-span-3">
                {{-- SELECT PROJECT --}}
                <x-cards>
                    <x-cards.heading>
                        <x-slot name="left">
                            <h1>Choose Payment Projects</h1>
                        </x-slot>
                    </x-cards.heading>

                    <x-cards.body :class="'space-y-2 my-2 pb-2'">
                        <x-forms.row
                            wire:model.live="project_id"
                            wire:target="addProject"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                            errorName="project_id"
                            name="project_id"
                            text="Project"
                            type="new_dropdown"
                            :options="$this->projects->where('show', false)"
                            >


                            {{-- <option value="" disabled>Select Project</option> --}}
                            {{-- @foreach ($projects->where('show', false) as $project)
                                <option value="{{$project->id}}">{{$project->name}}</option>
                            @endforeach --}}
                        </x-forms.row>

                        <x-forms.row
                            wire:click="addProject"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                            type="button"
                            errorName="project_id_DONT_SHOW_ERROR"
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
                                <h1 class="font-bold"><a href="{{route('projects.show', $project->id)}}" target="_blank">{{ $project->address }}</a></h1>
                                <span class="text-sm">{{ $project->project_name}}</span>
                            </x-slot>

                            <x-slot name="right">
                                <x-cards.button wire:click="$dispatchTo('bids.bid-create', 'addBids', { vendor: {{$vendor->id}}, project: {{$project->id}} })">
                                    Edit Bid
                                </x-cards.button>
                                <x-cards.button wire:click="removeProject({{$project_id}})" :button_color="'white'">
                                    Remove
                                </x-cards.button>
                            </x-slot>
                        </x-cards.heading>

                        {{-- ROWS --}}
                        <x-cards.body :class="'space-y-2 my-2 pb-2'">
                            {{-- VENDOR BIDS --}}
                            <x-forms.row
                                wire:model.live="projects.{{$project_id}}.vendor_bids_sum"
                                errorName="projects.{{$project_id}}.vendor_bids_sum"
                                name="projects.{{$project_id}}.vendor_bids_sum"
                                text="Total Bids"
                                type="number"
                                hint="$"
                                x-bind:disabled="true"
                                >
                            </x-forms.row>

                            {{-- VENDOR PROJECT SUM --}}
                            <x-forms.row
                                {{-- 09-05-2023 how to format wire:model.live --}}
                                wire:model.live="projects.{{$project_id}}.vendor_expenses_sum"
                                errorName="projects.{{$project_id}}.vendor_expenses_sum"
                                name="projects.{{$project_id}}.vendor_expenses_sum"
                                text="Total Paid"
                                type="number"
                                hint="$"
                                x-bind:disabled="true"
                                >
                            </x-forms.row>

                            {{-- AMOUNT --}}
                            <x-forms.row
                                wire:model.live.debounce.500ms="projects.{{$project_id}}.amount"
                                errorName="projects.{{$project_id}}.amount"
                                name="projects.{{$project_id}}.amount"
                                {{-- x-text="money(payment_projects.{{$index}}.amount)" --}}
                                text="Amount"
                                type="number"
                                hint="$"
                                textSize="xl"
                                placeholder="00.00"
                                inputmode="decimal"
                                step="0.01"
                                pattern="[0-9]*"
                                autofocus
                                >
                            </x-forms.row>

                            {{-- VENDOR PROJECT BALANCE --}}
                            <x-forms.row
                                wire:model.live="projects.{{$project_id}}.balance"
                                errorName="projects.{{$project_id}}.balance"
                                name="projects.{{$project_id}}.balance"
                                text="Balance"
                                type="number"
                                hint="$"
                                x-bind:disabled="true"
                                >
                            </x-forms.row>
                            {{-- total paid, bid, balance rows DISABLED --}}
                        </x-cards.body>
                    </x-cards>
                @endforeach

                <livewire:bids.bid-create />
                <livewire:vendor-docs.vendor-doc-create />
            </div>
        </div>
    </form>
</div>

