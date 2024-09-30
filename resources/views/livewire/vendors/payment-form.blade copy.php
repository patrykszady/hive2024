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
                <flux:card>
                    <flux:heading size="lg">Choose Payment Projects</flux:heading>
                    <flux:separator variant="subtle" />
                    <flux:input.group>
                        <flux:select wire:model.live="project_id">
                            <flux:option value="" readonly>Select Project...</flux:option>

                            @foreach($this->projects->where('show', false) as $project)
                                <flux:option value="{{$project->id}}">{{$project->name}}</flux:option>
                            @endforeach

                        </flux:select>

                        <flux:button variant="primary" wire:click="addProject" icon="receipt-percent">Add</flux:button>
                    </flux:input.group>
                </flux:card>

                {{-- PAYMENT PROJECTS --}}
                @foreach($projects->where('show', true) as $project_id => $project)
                    <flux:card class="space-y-6">
                        <div class="flex justify-between">
                            <div>
                                <flux:heading size="lg"><a href="{{route('projects.show', $project->id)}}" target="_blank">{{ $project->address }}</a></flux:heading>
                                <flux:subheading>{{ $project->project_name}}</flux:subheading>
                            </div>
                            <flux:button.group>
                                <flux:button size="sm" wire:click="$dispatchTo('bids.bid-create', 'addBids', { vendor: {{$vendor->id}}, project: {{$project->id}} })">Edit Bids</flux:button>
                                <flux:button size="sm" wire:click="removeProject({{$project_id}})">Remove</flux:button>
                            </flux:button.group>
                        </div>

                        <flux:separator variant="subtle" />

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
                    </flux:card>
                @endforeach

                <livewire:bids.bid-create />
                <livewire:vendor-docs.vendor-doc-create />
            </div>
        </div>
    </form>
</div>

