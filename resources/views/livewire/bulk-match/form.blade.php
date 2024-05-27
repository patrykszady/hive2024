<x-modal wire:model="showModal">
    <x-modal.panel>
        {{-- HEADER --}}
        <x-cards.heading>
            <x-slot name="left">
                <h1>Add New Automatic Vendor / Transaction</h1>
            </x-slot>
            <x-slot name="right">
            </x-slot>
        </x-cards.heading>

        <form wire:submit="save">
            {{-- ROWS --}}
            <x-cards.body :class="'space-y-4 my-4'">
                <div
                    x-data="{ any_amount: @entangle('form.any_amount'), match: @entangle('form.match') }"
                    >

                    {{-- VENDOR --}}
                    <div
                        x-show="!match"
                        >
                        <x-forms.row
                            wire:model.live="form.vendor_id"
                            errorName="form.vendor_id"
                            name="vendor_id"
                            x-bind:disabled="match"
                            text="Vendor"
                            type="dropdown"
                            >

                            <option value="" readonly>Select Vendor</option>
                            @foreach ($new_vendors as $vendor)
                                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                            @endforeach
                        </x-forms.row>
                    </div>

                    <div
                        x-show="match"
                        >
                        <x-forms.row
                            wire:model.live="form.vendor_id"
                            errorName="form.vendor_id"
                            name="vendor_id"
                            x-bind:disabled="match"
                            text="Vendor"
                            type="dropdown"
                            >

                            <option value="" readonly>Select Vendor</option>
                            @foreach ($existing_vendors as $vendor)
                                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                            @endforeach
                        </x-forms.row>
                    </div>

                    <br>

                    {{-- AMOUNT --}}
                    <x-forms.row
                        wire:model.live.debounce.250ms="form.amount"
                        errorName="form.amount"
                        name="amount"
                        text="Amount"
                        type="number"
                        hint="$"
                        textSize="xl"
                        placeholder="{{$form->any_amount == 1 ? 'Any Amount' : '00.00'}}"
                        inputmode="decimal"
                        x-bind:disabled="any_amount"
                        {{-- pattern="[-+,0-9.]*" --}}
                        step="0.01"
                        radioHint="Any $"
                        >

                        <x-slot name="hint_dropdown">
                            <label for="amount_type" class="sr-only">Match Amount Type</label>
                            <select
                                wire:model.live="form.amount_type"
                                errorName="form.amount_type"
                                x-bind:disabled="any_amount"
                                {{-- id="amount_type" --}}
                                name="amount_type"
                                class="h-full py-0 pl-3 text-gray-500 bg-transparent border-0 rounded-md pr-7 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                >
                                <option value="" readonly></option>
                                <option value="=">=</option>
                                <option value=">=">>=</option>
                                <option value="<="><=</option>
                                <option value=">">></option>
                                <option value="<"><</option>
                            </select>
                        </x-slot>

                        <x-slot name="radio">
                            <input
                                wire:model.live="form.any_amount"
                                {{-- x-bind:disabled="match.any_amount" --}}
                                {{-- id="form.any_amount" --}}
                                name="any_amount"
                                value="true"
                                type="checkbox"
                                class="w-4 h-4 ml-2 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                >
                        </x-slot>
                    </x-forms.row>
                </div>

                {{-- DISTRIBUTION --}}
                <x-forms.row
                    wire:model.live="form.distribution_id"
                    {{-- x-bind:disabled="split" --}}
                    errorName="form.distribution_id"
                    name="distribution_id"
                    text="Distribution"
                    type="dropdown"
                    {{-- radioHint="Split" --}}
                    >

                    <option
                        value=""
                        x-text="'Select Distribution'"
                        readonly
                        {{-- x-text="split ? 'Bulk Match is Split' : 'Select Distribution'" --}}
                        >
                    </option>

                    @foreach ($distributions as $distribution)
                        <option
                            value="{{$distribution->id}}"
                            >
                            {{$distribution->name}}
                        </option>
                    @endforeach
                </x-forms.row>

                {{-- DESC --}}
                <x-forms.row
                    wire:model="form.desc"
                    errorName="form.desc"
                    name="desc"
                    placeholder="Common Transaction Desc"
                    text="Description"
                    >
                </x-forms.row>

                <div
                    x-data="{ match: @entangle('form.match') }"
                    x-show="!match"
                    >
                    <x-misc.hr>Missing Expenses</x-misc.hr>
                    @if(!is_null($new_vendor))
                        {{-- @if(!$new_vendor->vendor_transactions->isEmpty()) --}}
                        @if(!is_null($new_vendor->vendor_transactions))
                            <x-cards.wrapper class="col-span-4 p-6 lg:col-span-2">
                                <x-cards.heading class="bg-color-none">
                                    <x-slot name="left">
                                        <h1>Vendor <b>Transactions</b></h1>
                                    </x-slot>
                                    <x-slot name="right">
                                        {{-- <x-cards.button wire:click="$dispatch('manualMatch')">
                                            Add Expenses For Selected
                                        </x-cards.button> --}}
                                    </x-slot>
                                </x-cards.heading>
                                <x-lists.ul>
                                    @foreach($new_vendor->vendor_transactions as $key => $transactions)
                                        @php
                                            $checkbox = [
                                                // checked vs unchecked
                                                'wire_click' => "checkbox($key)",
                                                'id' => "$key",
                                                'name' => "vendor_amount_group",
                                            ];
                                        @endphp
                                        <x-lists.search_li
                                            {{-- tpggle checkbox value --}}
                                            {{-- :line_details="" --}}
                                            :line_title="money($transactions->first()->amount) . ' | ' . $transactions->first()->plaid_merchant_description"
                                            :bubble_message="$transactions->count() . ' Transaction/s'"

                                            {{-- :line_title="'TEST titlte'" --}}
                                            :checkbox="$checkbox"
                                            >
                                        </x-lists.search_li>
                                    @endforeach
                                </x-lists.ul>
                            </x-cards.wrapper>
                        @endif
                        @if(!is_null($new_vendor->vendor_expenses))
                        <x-cards.wrapper class="col-span-4 p-6 lg:col-span-2">
                            <x-cards.heading class="bg-color-none">
                                <x-slot name="left">
                                    <h1>Vendor <b>Expenses</b></h1>
                                </x-slot>
                                <x-slot name="right">
                                    {{-- , ['vendor', '{{$vendor_add_type}}'] --}}
                                    {{-- <x-cards.button wire:click="$dispatch('manualMatch')">
                                        Add Expenses For Selected
                                    </x-cards.button> --}}
                                </x-slot>
                            </x-cards.heading>
                            <x-lists.ul>
                                @foreach($new_vendor->vendor_expenses as $amount => $expenses)
                                    {{-- @php
                                        $checkbox = [
                                            // checked vs unchecked
                                            'wire_click' => "checkbox($key)",
                                            'id' => "$key",
                                            'name' => "vendor_amount_group",
                                        ];
                                    @endphp --}}
                                    <x-lists.search_li
                                        {{-- tpggle checkbox value --}}
                                        {{-- :line_details="" --}}
                                        :line_title="money($amount)"
                                        :bubble_message="$expenses->count() . ' Expense/s'"

                                        {{-- :line_title="'TEST titlte'" --}}
                                        {{-- :checkbox="$checkbox" --}}
                                        >
                                    </x-lists.search_li>
                                @endforeach
                            </x-lists.ul>
                        </x-cards.wrapper>
                    @endif
                    @endif
                </div>
            </x-cards.body>

            {{-- FOOTER --}}
            <x-cards.footer>
                <button
                    type="button"
                    x-on:click="open = false"
                    class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm font-small hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                    Cancel
                </button>

                <x-forms.button
                    type="submit"
                    >
                    Create Bulk Match
                </x-forms.button>
            </x-cards.footer>
        </form>
    </x-modal.panel>
</x-modal>
