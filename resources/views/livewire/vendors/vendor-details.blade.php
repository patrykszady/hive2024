<x-lists.details_card>
    {{-- HEADING --}}
    <x-slot:heading>
        <div>
            <flux:heading size="lg" class="mb-0">Vendor Details</flux:heading>
            @if($registration)
                <flux:subheading>Confirm  information.</flux:subheading>
            @endif
        </div>

        @can('update', $vendor)
            @if(in_array($vendor->business_type, ["Sub", "DBA", "1099"]))
                @if($vendor->id != auth()->user()->vendor->id)
                    <flux:button.group>
                        <flux:button
                            variant="primary"
                            wire:navigate.hover
                            href="{{route('vendors.payment', $vendor->id)}}"
                            size="sm"
                            >
                            Vendor Payment
                        </flux:button>

                        <flux:button icon="chevron-down" size="sm">
                            {{-- EDIT VENDOR --}}
                            {{-- <flux:button
                                wire:click="$dispatchTo('vendors.vendor-create', 'editVendor', { vendor: {{$vendor->id}} })"
                                size="sm"
                                >
                                Edit Vendor
                            </flux:button> --}}
                        </flux:button>

                        {{-- <livewire:vendors.vendor-create /> --}}
                    </flux:button.group>
                @endif
            @endif
        @endcan
    </x-slot>

    {{-- DETAILS --}}
    <x-lists.details_list>
        <x-lists.details_item title="Business Name" detail="{!!$vendor->business_name!!}" />
        <x-lists.details_item title="Vendor Type" detail="{{$vendor->business_type}}" />
        @if($vendor->business_type != 'Retail')
            <x-lists.details_item title="Vendor Address" detail="{!!$vendor->full_address!!}" href="{{$vendor->getAddressMapURI()}}" target="_blank" />
        @endif
        @if(in_array($vendor->business_type, ["Sub", "DBA", "1099"]))
            <x-lists.details_item title="Business Phone" detail="{{$vendor->business_phone}}" />
            <x-lists.details_item title="Business Email" detail="{{$vendor->business_email}}" />
        @endif
    </x-lists.details_list>
</x-lists.details_card>


{{-- <div
    x-data="{ vendor_info: @entangle('registration') }"
    x-show="vendor_info"
    x-transition.duration.250ms
    >
    <x-cards.footer>
        <button></button>
        <x-cards.button
            wire:click="$dispatchTo('entry.vendor-registration', 'confirmProcessStep', { process_step: 'vendor_info' })"
            button_color=green
            >
            Confirm Details
        </x-cards.button>
    </x-cards.footer>
</div> --}}
