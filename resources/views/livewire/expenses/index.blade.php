<div class="max-w-3xl">
    <flux:card class="space-y-2">
        <div>
            <flux:heading size="lg">Filters</flux:heading>
        </div>

        <flux:separator variant="subtle" />

        <div class="grid grid-cols-3 gap-4">
            <flux:input wire:model.debounce.500ms.live="amount" label="Amount" icon="magnifying-glass" placeholder="123.45" />

            <flux:autocomplete label="Vendor" placeholder="Select Vendor..." icon="chevron-up-down">
                @foreach ($this->vendors as $vendor)
                    <flux:autocomplete.item value="{{$vendor->id}}">{{ $vendor->name }}</flux:autocomplete.item>
                @endforeach
            </flux:autocomplete>
        </div>
    </flux:card>

    <flux:card class="mt-4 space-y-2">
        <div>
            <flux:heading size="lg">Expenses</flux:heading>
        </div>

        <div class="space-y-2">
            <flux:table :paginate="$this->expenses">
                <flux:columns>
                    <flux:column>Amount</flux:column>
                    <flux:column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection" wire:click="sort('date')">Date</flux:column>
                    <flux:column >Vendor</flux:column>
                    <flux:column>Project</flux:column>
                    <flux:column>Status</flux:column>
                </flux:columns>

                <flux:rows>
                    @foreach ($this->expenses as $expense)
                        <flux:row :key="$expense->id">
                            <flux:cell
                                wire:click="$dispatchTo('expenses.expense-create', 'editExpense', { expense: {{$expense->id}}})"
                                variant="strong"
                                class="cursor-pointer"
                                >
                                {{ money($expense->amount) }}
                            </flux:cell>
                            <flux:cell>{{ $expense->date->format('m/d/Y') }}</flux:cell>
                            <flux:cell><a href="{{route('vendors.show', $expense->vendor->id)}}" target="_blank">{{Str::limit($expense->vendor->name, 20)}}</a></flux:cell>
                            <flux:cell>{{ Str::limit($expense->project->name, 25) }}</flux:cell>
                            <flux:cell>
                                <flux:badge size="sm" :color="$expense->status == 'Complete' ? 'green' : ($expense->status == 'No Transaction' ? 'yellow' : 'red')" inset="top bottom">{{ $expense->status }}</flux:badge>
                            </flux:cell>
                        </flux:row>
                    @endforeach
                </flux:rows>
            </flux:table>

            <livewire:expenses.expense-create />
        </div>
    </flux:card>

    <flux:card class="mt-8 space-y-2">
        <div>
            <flux:heading size="lg">Transactions</flux:heading>
        </div>

        <div class="space-y-6">
            {{-- wire:loading.class="opacity-50 text-opacity-40" --}}
            <flux:table :paginate="$this->transactions">
                <flux:columns>
                    <flux:column>Amount</flux:column>
                    <flux:column>Date</flux:column>
                    <flux:column>Vendor</flux:column>
                    <flux:column>Bank</flux:column>
                    <flux:column>Account</flux:column>
                </flux:columns>

                <flux:rows>
                    @foreach ($this->transactions as $transaction)
                        <flux:row :key="$transaction->id">
                            <flux:cell
                                wire:click="$dispatchTo('expenses.expense-create', 'createExpenseFromTransaction', { transaction: {{$transaction->id}}})"
                                class="cursor-pointer"
                                variant="strong"
                                >
                                {{ money($transaction->amount) }}
                            </flux:cell>
                            <flux:cell>{{ $transaction->transaction_date->format('m/d/Y') }}</flux:cell>
                            <flux:cell>{{ Str::limit($transaction->vendor->name != 'No Vendor' ? $transaction->vendor->name : $transaction->plaid_merchant_description, 35)}}</flux:cell>
                            <flux:cell>{{ $transaction->bank_account->bank->name }}</flux:cell>
                            <flux:cell>{{ $transaction->bank_account->account_number }}</flux:cell>
                        </flux:row>
                    @endforeach
                </flux:rows>
            </flux:table>
        </div>
    </flux:card>
</div>
{{--

<div>
    <x-cards.heading>
        <div class="mx-auto">
            <div>
                <select
                    wire:model.live="bank_plaid_ins_id"
                    id="bank_plaid_ins_id"
                    name="bank_plaid_ins_id"
                    class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="" readonly>All Banks</option>
                    @foreach($banks as $institution_id => $bank)
                        <option value="{{$institution_id}}">{{$bank->first()->name}}</option>
                    @endforeach
                </select>
            </div>
            @if(!empty($bank_owners))
                <div>
                    <select
                        wire:model.live="bank_owner"
                        id="bank_owner"
                        name="bank_owner"
                        class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="" readonly>All Owners</option>
                        @foreach($bank_owners as $owner)
                            <option value="{{$owner}}">{{$owner}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </x-cards.heading>
</div> --}}
