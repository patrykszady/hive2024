<flux:modal name="user_form_modal" class="space-y-2">
    <div class="flex justify-between">
        <flux:heading size="lg">{{$view_text['card_title']}}</flux:heading>
    </div>

    <flux:separator variant="subtle" />

    <form wire:submit="{{$view_text['form_submit']}}" class="grid gap-6">
        {{-- PHONE --}}
        <flux:input
            wire:model.live.debounce.1000ms="user_cell"
            label="User Cell Phone"
            type="number"
            size="lg"
            maxlength="10"
            minlength="10"
            inputmode="numeric"
            placeholder="8474304439"
            autofocus
        />

        {{-- 1/12/2023 if no user_cell or if updated ONLY --}}
        <div
            x-data="{ user_cell: @entangle('user_cell'), user_form: @entangle('user_form') }"
            x-show="user_cell.length == 10 && !user_form"
            >
            <flux:button
                wire:click="user_cell_find"
                variant="primary"
                class="w-full"
                >
                Search User
            </flux:button>
        </div>

        {{-- USER DETAILS --}}
        <div
            x-data="{ open: @entangle('user_form'), user: @entangle('form.user') }"
            x-show="open"
            class="my-4 space-y-4"
            >
            <flux:input
                wire:model.live.debounce.500ms="form.first_name"
                x-bind:disabled="user"
                label="First Name"
                type="text"
                placeholder="First Name"
            />
            <flux:input
                wire:model.live.debounce.500ms="form.last_name"
                x-bind:disabled="user"
                label="Last Name"
                type="text"
                placeholder="Last Name"
            />
            <flux:input
                wire:model.live.debounce.500ms="form.email"
                x-bind:disabled="user"
                label="Email"
                placeholder="Email"
            />

            {{-- save/create User here if not yet saved --}}
            <div
                x-data="{ user: @entangle('form.user') }"
                x-show="!user"
                x-transition
                class="my-4 space-y-4"
                >
                <flux:button
                    wire:click="save_user_only"
                    variant="primary"
                    class="w-full"
                    >
                    Save User
                </flux:button>
            </div>
            {{-- CREATE/ATTACH 1099 / SUB Vendor / PAYROLL --}}
            {{--10-5-2024  CODE IN form_copy.blade.php --}}
            {{-- <div
                x-data="{ via_vendor: @entangle('form.via_vendor'), model: @entangle('model.id') }"
                x-show="via_vendor && model == 'NEW'"
                x-transition
                class="my-4 space-y-4"
                > --}}

                {{-- USER / VENDOR ROLE --}}
                <flux:select label="User Role" wire:model.live="form.role">
                    <flux:option value="" readonly>Select Role</flux:option>
                    <flux:option value="1">Admin</flux:option>
                    <flux:option value="2">Team Member</flux:option>
                </flux:select>

                <div
                    x-data="{ role: @entangle('form.role')}"
                    x-show="role == 2 ? true : false"
                    x-transition
                    class="my-4 space-y-4"
                    >

                    {{-- VIA VENDOR --}}
                    <flux:select label="Via Vendor" wire:model.live="form.via_vendor" placeholder="Choose Via Vendor...">
                        {{-- <flux:option value="" readonly>Select Role</flux:option> --}}
                        @foreach($via_vendors as $via_vendor)
                            <flux:option value="{{$via_vendor->id}}">{{$via_vendor->business_name}}, {{$via_vendor->business_type}}</flux:option>
                        @endforeach
                        {{-- disabled if !$via_vendors->isEmpty --}}
                        <flux:option value="NEW_VIA" readonly>New Vendor</flux:option>
                    </flux:select>

                    <div
                        x-data="{ via_vendor: @entangle('form.via_vendor')}"
                        x-show="via_vendor == 'NEW_VIA' ? true : false"
                        x-transition
                        class="my-4 space-y-4"
                        >
                        {{-- create new vendor for user being added ... --}}
                        <flux:button
                            wire:click="create_via_vendor"
                            variant="primary"
                            class="w-full"
                            >
                            Create Vendor
                        </flux:button>

                        {{-- <livewire:vendors.vendor-create /> --}}
                    </div>
                </div>

                {{-- USER / VENDOR HOURLY PAY --}}
                <div
                    x-data="{ via_vendor: @entangle('form.via_vendor'), role: @entangle('form.role') }"
                    x-show="(via_vendor && via_vendor != 'NEW_VIA') || role == 1"
                    x-transition
                    class="my-4 space-y-4"
                    >
                    {{-- USER / VENDOR HOURLY PAY --}}
                    <flux:input
                        wire:model.live.debounce.500ms="form.hourly_rate"
                        label="User Hourly Pay"
                        type="number"
                        size="lg"
                        inputmode="numeric"
                        placeholder="10"
                    />
                </div>
            {{-- </div> --}}
        </div>

        <div class="flex space-x-2 sticky bottom-0">
            <flux:spacer />

            <div
                x-data="{open: @entangle('user_form'), user: @entangle('form.user')}"
                x-show="open && user"
                x-transition
                >
                <flux:button type="submit" variant="primary">{{$view_text['button_text']}}</flux:button>
            </div>
        </div>
    </form>
</flux:modal>
