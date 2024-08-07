<x-modal wire:model="showModal">
    <x-modal.panel>
        <form wire:submit="save">
            {{-- HEADER --}}
            <x-cards.heading>
                <x-slot name="left">
                    <h1>Associated Expenses</h1>
                </x-slot>
            </x-cards.heading>

            {{-- ROWS --}}
            <x-cards.body :class="'space-y-4 my-4'">
                <x-forms.row
                    wire:model.live="associate_expense"
                    errorName="associate_expense"
                    name="associate_expense"
                    text="Associated Expense"
                    type="radiogroup"
                    :data="[
                        'wire_model' => $this->expenses,
                        'radio_details_left' => [
                            'title' => 'amount',
                            'desc' => 'date',
                            ],
                        'radio_details_right' => [
                            'title' => '',
                            'desc' => '',
                            ]
                        ]"
                    >
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
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 ml-3 text-sm text-white bg-indigo-600 border border-transparent rounded-md shadow-sm font-small disabled:opacity-50 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                    Associate Expenses
                </button>
            </x-cards.footer>
        </form>
    </x-modal.panel>
</x-modal>
