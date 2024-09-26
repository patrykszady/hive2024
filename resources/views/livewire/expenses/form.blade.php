
    {{-- <flux:modal.trigger name="edit-profile">
        <flux:button>Edit profile</flux:button>
    </flux:modal.trigger> --}}

    {{-- variant="flyout" --}}
    <flux:modal name="expenses_form_modal" class="space-y-8">
        <flux:heading size="lg">Update expense</flux:heading>
        <flux:separator variant="subtle" />

        <form wire:submit="{{$view_text['form_submit']}}" class="grid gap-6">
            {{-- AMOUNT --}}
            <div
                x-data="{ amount: @entangle('form.amount'), save_form: @entangle('view_text.form_submit'), expense_transactions: @entangle('form.expense_transactions_sum') }"
                >
                <flux:input
                    wire:model.live.debounce.500ms="form.amount"
                    x-bind:disabled="save_form == 'save' || expense_transactions"
                    label="Amount"
                    type="number"
                    size="lg"
                    placeholder="123.45"
                />
            </div>

            {{-- DATE --}}
            <flux:input
                wire:model.live.debounce.500ms="form.date"
                label="Date"
                type="date"
            />

            {{-- VENDOR --}}
            <flux:field>
                <flux:label>Vendor</flux:label>

                {{-- <flux:autocomplete --}}
                <flux:select wire:model.live="form.vendor_id" placeholder="Choose vendor...">
                    @foreach($vendors as $vendor)
                        <flux:option value="{{$vendor->id}}">{{$vendor->name}}</flux:option>
                    @endforeach
                </flux:select>

                <flux:error name="form.vendor_id" />
            </flux:field>

            {{-- PROJECT --}}
            <div
                x-data="{ open: @entangle('form.vendor_id'), split: @entangle('split') }"
                x-show="open"
                x-transition
                >
                <flux:field>
                    <flux:label>Project</flux:label>

                    <flux:select wire:model.live="form.project_id" placeholder="Choose project...">
                        <flux:option value="" readonly>No Project</flux:option>

                        @foreach($projects as $project)
                            <flux:option value="{{$project->id}}">{{$project->name}}</flux:option>
                        @endforeach

                        <flux:option disabled>--------------</flux:option>

                        @foreach($distributions as $distribution)
                            <flux:option value="D:{{$distribution->id}}">{{$distribution->name}}</flux:option>
                        @endforeach
                    </flux:select>

                    <flux:error name="form.project_id" />
                </flux:field>
            </div>

            {{-- SPLITS --}}

            {{-- PAID BY --}}
            <div
                x-data="{ open: @entangle('form.project_id'), splits: @entangle('splits'), split: @entangle('split') }"
                x-show="splits && split || open"
                x-transition
                >
                <flux:field>
                    <flux:label>Paid By</flux:label>

                    <flux:select wire:model.live="form.paid_by" placeholder="Choose who paid...">
                        <flux:option>{{auth()->user()->vendor->name}}</flux:option>
                        @foreach($employees as $employee)
                            <flux:option value="{{$employee->id}}">{{$employee->first_name}}</flux:option>
                        @endforeach
                    </flux:select>

                    <flux:error name="form.paid_by" />
                </flux:field>
            </div>

            {{-- CHECK --}}

            {{-- RECEIPT --}}
            <div
                x-data="{ open: @entangle('form.project_id'), splits: @entangle('splits'), split: @entangle('split') }"
                x-show="splits && split || open"
                x-transition
                >

                <flux:input
                    wire:model="form.receipt_file"
                    type="file"
                    x-bind:disabled="save_form == 'save' || expense_transactions"
                    label="Receipt File"
                />
                {{-- LOADING STATES --}}
            </div>

            {{-- REIMBURSEMNT --}}
            <div
                x-data="{ open: @entangle('form.project_id'), project_completed: @entangle('form.project_completed') }"
                x-show="open"
                x-transition
                >
                <flux:field>
                    <flux:label>Reimbursment</flux:label>

                    <flux:select wire:model.live="form.reimbursment" placeholder="Choose reimbursment...">
                        <flux:option x-bind:selected="split == true ? true : false">None</flux:option>
                        <flux:option x-bind:disabled="project_completed">Client</flux:option>
                        @foreach ($via_vendor_employees as $employee)
                            <flux:option value="{{$employee->id}}">{{$employee->first_name}}</flux:option>
                        @endforeach
                    </flux:select>

                    <flux:error name="form.reimbursment" />
                </flux:field>
            </div>

            {{-- PO/INVOICE --}}
            <div
                x-data="{ open: @entangle('form.project_id'), splits: @entangle('splits'), split: @entangle('split') }"
                x-show="splits && split || open"
                x-transition
                >

                <flux:input
                    wire:model.live.debounce.500ms="form.invoice"
                    label="Invoice"
                    type="text"
                    placeholder="Invoice/PO"
                />
            </div>

            {{-- NOTES --}}
            <div
                x-data="{ open: @entangle('form.project_id'), splits: @entangle('splits'), split: @entangle('split') }"
                x-show="splits && split || open"
                x-transition
                >
                <flux:textarea
                    wire:model.live.debounce.500ms="form.note"
                    label="Notes"
                    rows="auto"
                    resize="none"
                    placeholder="Notes"
                />
            </div>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">{{$view_text['button_text']}}</flux:button>
            </div>
        </form>
    </flux:modal>

