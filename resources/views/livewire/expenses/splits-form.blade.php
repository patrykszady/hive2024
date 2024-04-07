<x-modals.modal>
    <form wire:submit="{{$view_text['form_submit']}}">
        <x-cards.heading>
            <x-slot name="left">
                <h1>Expense Project Splits</h1>
            </x-slot>

            <x-slot name="right">
                <button
                    wire:click="$dispatch('addSplit')"
                    type="button"
                    class="inline-flex justify-center px-4 py-2 ml-3 text-sm text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                    Add Another Split
                </button>
            </x-slot>
        </x-cards.heading>

        <x-cards.body>
            @if(!is_null($expense_splits))
                @foreach ($expense_splits as $index => $split)
                    <x-cards.heading>
                        <x-slot name="left">
                            <h1 class="text-base font-semibold leading-6 text-gray-900">Split {{$index + 1}}</h1>
                        </x-slot>

                        <x-slot name="right">
                            {{-- cannot remove if splits is equal to 2 or less --}}
                            @if($loop->count > 2)
                                <button
                                    type="button"
                                    wire:click="removeSplit({{$index}})"
                                    x-transition
                                    class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                    Remove Split
                                </button>
                            @endif
                        </x-slot>
                    </x-cards.heading>
                    <div
                        wire:key="expense-splits-{{ $index }}"
                        class="mt-2 space-y-2"
                        >
                        {{-- ROWS --}}

                        {{-- show expense receipt line items if isset --}}
                        @if($expense_line_items)
                            <div class="px-4 sm:px-6 lg:px-4">
                                {{-- <div class="sm:flex sm:items-center">
                                    <div class="sm:flex-auto">
                                        <h1 class="text-base font-semibold leading-6 text-gray-900">Receipt</h1>
                                        <p class="mt-2 text-sm text-gray-700">Receipt Items. Choose Items belonging to this Split</p>
                                    </div>
                                    <!-- <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                        <button type="button"
                                            class="block px-3 py-2 text-sm font-semibold text-center text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add
                                            user</button>
                                    </div> -->
                                </div> --}}
                                <div class="flow-root mt-8">
                                    {{-- <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"> --}}
                                    <div class="">
                                        <div class="">
                                            <div class="relative">
                                                <!-- Selected row actions, only show when rows are selected. -->
                                                <!-- <div class="absolute top-0 flex items-center h-12 space-x-3 bg-white left-14 sm:left-12"> -->
                                                <!--   <button type="button" class="inline-flex items-center px-2 py-1 text-sm font-semibold text-gray-900 bg-white rounded shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">Bulk edit</button> -->
                                                <!--   <button type="button" class="inline-flex items-center px-2 py-1 text-sm font-semibold text-gray-900 bg-white rounded shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">Delete all</button> -->
                                                <!-- </div> -->

                                                <table class="min-w-full divide-y divide-gray-300 table-fixed">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="relative px-7 sm:w-12 sm:px-6">
                                                                {{-- <input
                                                                    type="checkbox"
                                                                    class="absolute w-4 h-4 -mt-2 text-indigo-600 border-gray-300 rounded left-4 top-1/2 focus:ring-indigo-600"> --}}
                                                            </th>
                                                            <th scope="col"
                                                                class="py-3.5 pr-3 text-left text-sm font-semibold text-gray-900">Desc
                                                            </th>
                                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Qty
                                                            </th>
                                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Price
                                                            </th>
                                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total
                                                            </th>
                                                            {{-- <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                                                                <span class="sr-only">Edit</span>
                                                            </th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @foreach($expense_line_items->items as $line_item_index => $line_item)
                                                            <!-- Selected: "bg-gray-50" -->
                                                            <tr class="{{$split['items'] && $split['items'][$line_item_index]['checkbox'] == TRUE ? 'bg-gray-50' : ''}}">
                                                                <td class="relative px-7 sm:w-12 sm:px-6">
                                                                    <!-- Selected row marker, only show when row is selected. -->
                                                                    @if($split['items'] && $split['items'][$line_item_index]['checkbox'] == TRUE)
                                                                        <div class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"></div>
                                                                    @endif

                                                                    <input
                                                                        {{-- || $line_item->split_index == NULL --}}
                                                                        type="checkbox"
                                                                        @disabled(isset($line_item->split_index) ? $line_item->split_index != $index : FALSE)
                                                                        wire:model.live="expense_splits.{{$index}}.items.{{$line_item_index}}.checkbox"
                                                                        class="absolute w-4 h-4 -mt-2 text-indigo-600  {{isset($line_item->split_index) ? $line_item->split_index != $index || $line_item->split_index == NULL ? 'border-gray-200' : 'border-gray-300' : 'border-gray-300'}} rounded left-4 top-1/2 focus:ring-indigo-600"
                                                                    >
                                                                </td>
                                                                <!-- Selected: "text-indigo-600", Not Selected: "text-gray-900" -->
                                                                <td class="py-4 pr-3 text-sm {{$split['items'] && $split['items'][$line_item_index]['checkbox'] == TRUE ? 'text-indigo-600' : (isset($line_item->split_index) ? $line_item->split_index != $index || $line_item->split_index == NULL ? 'text-gray-200' : 'text-gray-600' : 'text-gray-600')}} whitespace-nowrap">{{$line_item->desc}}</td>
                                                                <td class="px-3 py-4 text-sm {{isset($line_item->split_index) ? $line_item->split_index != $index || $line_item->split_index == NULL ? 'text-gray-200' : 'text-gray-500' : 'text-gray-500'}} whitespace-nowrap">{{$line_item->quantity}}</td>
                                                                <td class="px-3 py-4 text-sm {{isset($line_item->split_index) ? $line_item->split_index != $index || $line_item->split_index == NULL ? 'text-gray-200' : 'text-gray-500' : 'text-gray-500'}} whitespace-nowrap">{{money($line_item->price_each)}}</td>
                                                                <td class="px-3 py-4 text-sm {{isset($line_item->split_index) ? $line_item->split_index != $index || $line_item->split_index == NULL ? 'text-gray-200' : 'text-gray-500' : 'text-gray-500'}} whitespace-nowrap">{{money($line_item->price_total)}}</td>
                                                                {{-- <td class="py-4 pl-3 pr-4 text-sm font-medium text-right whitespace-nowrap sm:pr-3">
                                                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span
                                                                            class="sr-only">, Lindsay
                                                                            Walton</span></a>
                                                                </td> --}}
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Just show amount per split --}}
                        <x-forms.row
                            wire:model.live.debounce.200ms="expense_splits.{{ $index }}.amount"
                            errorName="expense_splits.{{ $index }}.amount"
                            name="amount"
                            text="Amount"
                            type="number"
                            hint="$"
                            textSize="xl"
                            placeholder="00.00"
                            inputmode="decimal"
                            pattern="[0-9]*"
                            step="0.01"
                            {{-- x-bind:disabled="{{$expense_line_items ? TRUE : FALSE}}" --}}
                            >
                        </x-forms.row>
                        {{-- {{money($this->split_amount)}} --}}
                        <x-forms.row
                            wire:model.live="expense_splits.{{ $index }}.project_id"
                            errorName="expense_splits.{{ $index }}.project_id"
                            name="project_id"
                            text="Project"
                            type="dropdown"
                            >
                            <option value="" readonly x-text="'Select Project'">
                            </option>

                            @foreach ($projects as $project)
                                <option value="{{$project->id}}">{{$project->name}}</option>
                            @endforeach

                            <option disabled>----------</option>

                            @foreach ($distributions as $distribution)
                                <option
                                    value="D:{{$distribution->id}}"
                                    >
                                    {{$distribution->name}}
                                </option>
                            @endforeach
                        </x-forms.row>

                        <x-forms.row
                            wire:model.live="expense_splits.{{ $index }}.reimbursment"
                            errorName="expense_splits.{{ $index }}.reimbursment"
                            name="reimbursment"
                            text="Reimbursment"
                            type="dropdown"
                            >
                            <option value="">None</option>
                            <option value="Client">Client</option>
                        </x-forms.row>

                        <x-forms.row
                            wire:model.live="expense_splits.{{ $index }}.note"
                            errorName="expense_splits.{{ $index }}.note"
                            name="note"
                            text="Note"
                            type="textarea"
                            rows="1"
                            placeholder="Notes about this expense split."
                            >
                        </x-forms.row>
                        <hr>
                    </div>
                @endforeach
            @endif
        </x-cards.body>

        <x-cards.footer>
            <button
                type="button"
                wire:click="$dispatch('resetSplits')"
                x-on:click="open = false"
                class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                Cancel
            </button>

            <button
                type="button"
                disabled
                class="px-4 py-2 text-lg font-medium text-center text-gray-900 border-2 border-indigo-600 rounded-md shadow-sm cursor-text focus:outline-none"
                >
                {{money($this->splits_sum)}}
            </button>

            <x-forms.button
                type="submit"
                >
                {{$view_text['button_text']}}
            </x-forms.button>

            {{-- if error bag has expense_splits_total_match --}}
            <x-slot name="bottom">
                <x-forms.error errorName="expense_splits_total_match" />
            </x-slot>
        </x-cards.footer>
    </form>
</x-modals.modal>
