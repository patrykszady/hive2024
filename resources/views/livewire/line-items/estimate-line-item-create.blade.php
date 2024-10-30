<flux:modal name="estimate_line_item_form_modal" class="space-y-2">
    <div class="flex justify-between">
        <flux:heading size="lg">{{$view_text['card_title']}}</flux:heading>
    </div>

    <flux:separator variant="subtle" />

    <form wire:submit="{{$view_text['form_submit']}}" class="grid gap-6">
        <div
            x-data="{ openDropdown: false, search: @entangle('search'), edit_line_item: @entangle('edit_line_item') }"
            >
            {{-- SEARCH DROPDOWN --}}
            <x-forms.row
                x-on:click="openDropdown = true, search = ''"
                x-bind:disabled="edit_line_item"
                errorName="search"
                name="search"
                text="Select Line Item"
                type="search_dropdown"
                placeholder="Search Line Items"
                >

                <x-slot:rowslot>
                    <div
                        x-show="openDropdown && !edit_line_item"
                        x-transition
                        class="py-2 mt-1 overflow-y-auto bg-white border rounded-md shadow-lg max-h-64"
                        >
                        <x-lists.ul x-on:click="openDropdown = false">
                            @foreach($line_items_test as $line_item)
                                @php
                                    $line_details = [
                                        1 => [
                                            'text' => $line_item->desc,
                                            'icon' => 'M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z'
                                            ],
                                        ];
                                @endphp

                                <x-lists.search_li
                                    wire:click="selected_line_item({{$line_item->id}})"
                                    {{-- wire:click="$dispatchTo('line-items.line-item-create', 'editItem', { lineItemId: {{$line_item->id}} })" --}}
                                    :line_details="$line_details"
                                    :line_title="$line_item->name"
                                    :bubble_message="$line_item->category . '/' . $line_item->sub_category"
                                    >
                                </x-lists.search_li>
                            @endforeach
                        </x-lists.ul>
                    </div>
                </x-slot>
            </x-forms.row>
        </div>

        <div
            x-data="{ open: @entangle('line_item_id') }"
            x-show="open"
            x-transition
            class="my-4 space-y-4"
            >
            {{-- DESCRIPTION --}}
            <flux:textarea
                wire:model.live.debounce.500ms="form.desc"
                label="Description"
                rows="auto"
                resize="none"
                placeholder=""
            />

            {{-- NOTES --}}
            <flux:textarea
                wire:model.live.debounce.500ms="form.notes"
                label="Notes"
                rows="auto"
                resize="none"
                placeholder=""
            />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {{-- CATEGORY --}}
                <flux:input wire:model.live.debounce.500ms="form.category" label="Category" placeholder="Category" />

                {{-- SUB CATEGORY --}}
                <flux:input wire:model.live.debounce.500ms="form.sub_category" label="Sub Category" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {{-- UNIT TYPE --}}
                <flux:select wire:model="form.unit_type" label="Unit Type" placeholder="Choose unit type...">
                    @include('livewire.line-items._unit_type_options')
                </flux:select>

                {{-- COST --}}
                <flux:input
                    wire:model.live.debounce.500ms="form.cost"
                    label="Amount"
                    type="number"
                    inputmode="decimal"
                    pattern="[0-9]*"
                    step="0.01"
                    placeholder="00.00"
                />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {{-- QUANTITY --}}
                <flux:input
                    wire:model.live.debounce.500ms="form.quantity"
                    label="Quantity"
                    type="number"
                    inputmode="numeric"
                    step=".1"
                    min=".1"
                    placeholder="1"
                />

                {{-- TOTAL --}}
                <flux:input
                    wire:model.live.debounce.500ms="form.total"
                    label="Total"
                    disabled
                    type="number"
                    inputmode="decimal"
                />
            </div>
        </div>

        <div class="flex space-x-2 sticky bottom-0">
            <flux:spacer />

            <flux:button wire:click="removeFromEstimate" variant="danger">Remove</flux:button>
            <flux:button type="submit" variant="primary">{{$view_text['button_text']}}</flux:button>
        </div>
    </form>
</flux:modal>
