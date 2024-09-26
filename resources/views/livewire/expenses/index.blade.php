<div class="max-w-3xl">
    <flux:card class="space-y-2">
        <div>
            <flux:heading size="lg">Expense Filters</flux:heading>
        </div>

        <flux:separator variant="subtle" />

        <div class="grid grid-cols-3 gap-4">
            <flux:input wire:model.debounce.800ms.live="amount" label="Amount" icon="magnifying-glass" placeholder="123.45" />

            {{-- wire:model="state"  --}}
            <flux:autocomplete label="Vendor" placeholder="Select Vendor..." icon="chevron-up-down">
                @foreach ($this->vendors as $vendor)
                    <flux:autocomplete.item>{{ $vendor->name }}</flux:autocomplete.item>
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
                    {{-- sortable :sorted="$sortBy === 'amount'" :direction="$sortDirection" wire:click="sort('amount')"> --}}
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
                                class="flex items-center gap-3 cursor-pointer"
                                >
                                {{ money($expense->amount) }}
                                {{-- <a href="#">{{ money($expense->amount) }}</a> --}}
                            </flux:cell>
                            <flux:cell>{{ $expense->date->format('m/d/Y') }}</flux:cell>
                            <flux:cell>{{ Str::limit($expense->vendor->name, 20)  }}</flux:cell>
                            <flux:cell>{{ Str::limit($expense->project->name, 25) }}</flux:cell>

                            {{--
                            <flux:cell align="right">
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                            </flux:cell>
                            --}}
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
            <flux:table :paginate="$this->transactions">
                <flux:columns>
                    {{-- sortable :sorted="$sortBy === 'amount'" :direction="$sortDirection" wire:click="sort('amount')"> --}}
                    <flux:column>Amount</flux:column>
                    {{-- sortable :sorted="$sortBy === 'transaction_date'" :direction="$sortDirection" wire:click="sort('transaction_date')" --}}
                    <flux:column>Date</flux:column>
                    <flux:column>Vendor</flux:column>
                    <flux:column>Bank</flux:column>
                    <flux:column>Account</flux:column>
                </flux:columns>

                <flux:rows>
                    @foreach ($this->transactions as $transaction)
                        <flux:row :key="$transaction->id">
                            <flux:cell class="flex items-center gap-3" variant="strong">{{ money($transaction->amount) }}</flux:cell>
                            <flux:cell>{{ $transaction->transaction_date->format('m/d/Y') }}</flux:cell>
                            <flux:cell>{{ Str::limit($transaction->vendor->name, 20)  }}</flux:cell>
                            <flux:cell>{{ $transaction->bank_account->bank->name }}</flux:cell>
                            <flux:cell>{{ $transaction->bank_account->account_number }}</flux:cell>
                            {{--
                            <flux:cell align="right">
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                            </flux:cell>
                            --}}
                        </flux:row>
                    @endforeach
                </flux:rows>
            </flux:table>
        </div>
    </flux:card>

</div>
