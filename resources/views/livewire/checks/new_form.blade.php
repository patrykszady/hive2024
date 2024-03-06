<x-modals.modal>
    <form wire:submit="{{$view_text['form_submit']}}">
        <x-cards.heading>
            <x-slot name="left">
                <h1>{{$view_text['card_title']}}</h1>
            </x-slot>
        </x-cards.heading>

        <x-cards.body :class="'space-y-2 my-2'">
            <x-forms.row
                wire:model.live="form.bank_account_id"
                errorName="form.bank_account_id"
                name="bank_account_id"
                text="Bank"
                type="dropdown"
                {{-- x-bind:disabled="{{$expense_update ? TRUE : FALSE}}" --}}
                >

                <option value="" readonly>Select Bank</option>
                @foreach ($bank_accounts as $index => $bank_account)
                    <option value="{{$bank_account->id}}">
                        {{$bank_account->getNameAndType()}}
                    </option>
                @endforeach
            </x-forms.row>
            <div
                x-data="{ bank_account: @entangle('form.bank_account_id') }"
                x-show="bank_account"
                x-transition
                class="mt-2 space-y-2"
                >
                <x-forms.row
                    wire:model.live="form.check_type"
                    {{-- x-bind:disabled="{{$expense_update ? TRUE : FALSE}}" --}}
                    errorName="form.check_type"
                    name="check_type"
                    text="Type"
                    type="dropdown"
                    >
                    <option value="" readonly x-text="'Select Payment Type'"></option>
                    <option value="Check" x-text="'Check'"></option>
                    <option value="Transfer" x-text="'Transfer'"></option>
                    <option value="Cash" x-text="'Cash'"></option>
                </x-forms.row>

                <div
                    x-data="{ check_type: @entangle('form.check_type') }"
                    x-show="check_type == 'Check'"
                    x-transition
                    >
                    <x-forms.row
                        wire:model.live="form.check_number"
                        {{-- x-bind:disabled="{{$expense_update ? TRUE : FALSE}}" --}}
                        errorName="form.check_number"
                        name="check_number"
                        text="Check Number"
                        type="number"
                        placeholder="Check Number"
                        inputmode="numeric"
                        step="1"
                        >
                    </x-forms.row>
                </div>
            </div>
        </x-cards.body>

        <x-cards.footer>
            <button
                type="button"
                x-on:click="open = false"
                class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                Cancel
            </button>

            <button
                type="submit"
                class="inline-flex items-center px-4 py-2 ml-3 text-sm text-white bg-indigo-600 border border-transparent rounded-md shadow-sm disabled:opacity-50 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                {{$view_text['button_text']}}
            </button>
        </x-cards.footer>
    </form>
</x-modals.modal>

