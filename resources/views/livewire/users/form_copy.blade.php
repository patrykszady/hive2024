{{-- USER DETAILS --}}
<div
    x-data="{ open: @entangle('user_form'), user: @entangle('form.user') }"
    x-show="open"
    x-transition
    class="my-4 space-y-4"
    >
    {{-- CREATE/ATTACH 1099 / SUB Vendor / PAYROLL --}}
    <div
        x-data="{ via_vendor: @entangle('via_vendor'), model: @entangle('model.id') }"
        x-show="via_vendor && model != 'NEW'"
        x-transition.duration.900ms
        class="my-4 space-y-4"
        >
        <hr>
        {{-- USER / VENDOR ROLE --}}
        <x-forms.row
            wire:model="form.role"
            errorName="form.role"
            name="form.role"
            text="User Role"
            type="dropdown"
            {{-- :disabled="isset($model) ? $model['id'] == 'NEW' ? true : false : false" --}}
            autofocus
            >
            <option value="" readonly>Select Role</option>
            <option value="1">Admin</option>
            <option value="2">Team Member</option>
        </x-forms.row>

        <div
            x-data="{ role: @entangle('form.role')}"
            x-show="role == 2 ? true : false"
            x-transition
            class="my-4 space-y-4"
            >
            {{-- VIA VENDOR --}}
            <x-forms.row
                wire:model="form.via_vendor"
                errorName="form.via_vendor"
                name="form.via_vendor"
                text="Via Vendor"
                type="dropdown"
                autofocus
                >
                <option value="" readonly>Select Vendor</option>
                @foreach($via_vendors as $via_vendor)
                    <option value="{{$via_vendor->id}}">{{$via_vendor->business_name}}, {{$via_vendor->business_type}}</option>
                @endforeach
                {{-- disabled if !$via_vendors->isEmpty --}}
                <option value="NEW_VIA" readonly>New Vendor</option>
            </x-forms.row>


            <div
                x-data="{ via_vendor: @entangle('form.via_vendor')}"
                x-show="via_vendor == 'NEW_VIA' ? true : false"
                x-transition
                class="my-4 space-y-4"
                >
                {{-- create new vendor for user being added ... --}}
                <x-forms.row
                    wire:click="create_via_vendor"
                    {{-- wire:click="$dispatchTo('vendors.vendor-create', 'viaVendor', { user: '{{$form->user}}', business_name: '{{$form->business_name}}' })" --}}
                    {{-- wire:click="$dispatchTo('users.user-create', 'newMember', { model: 'vendor', model_id: '{{$vendor_add_type}}' })" --}}
                    {{-- wire:click="$dispatchTo('vendors.vendor-create', 'via')" --}}
                    errorName=""
                    name=""
                    text=""
                    type="button"
                    buttonText="Create Vendor"
                    >
                </x-forms.row>
                {{-- <livewire:vendors.vendor-create /> --}}
            </div>
        </div>
        {{-- USER / VENDOR HOURLY PAY --}}
        {{-- <x-forms.row
            wire:model="form.hourly_rate"
            errorName="form.hourly_rate"
            name="form.hourly_rate"
            text="User Hourly Pay"
            type="number"
            inputmode="numeric"
            placeholder="21"
            >
        </x-forms.row> --}}
        <div
            x-data="{ via_vendor: @entangle('form.via_vendor'), role: @entangle('form.role') }"
            {{-- 2249993881 --}}
            x-show="(via_vendor && via_vendor != 'NEW_VIA') || role == 1"
            x-transition
            class="my-4 space-y-4"
            >
            {{-- USER / VENDOR HOURLY PAY --}}
            <x-forms.row
                wire:model="form.hourly_rate"
                errorName="form.hourly_rate"
                name="hourly_rate"
                text="User Hourly Pay"
                type="number"
                inputmode="numeric"
                placeholder="21"
                >
            </x-forms.row>
        </div>
    </div>
</div>



{{-- <livewire:clients.client-create /> --}}
{{-- @if($model['id'] != 'NEW')
<livewire:vendors.vendor-create />
@endif --}}
