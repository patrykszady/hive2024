<div>
	<x-page.top
        class="lg:max-w-5xl max-w-xl"
        h1="{!! $vendor->name !!}"
        p=""
        {{-- right_button_href="{{auth()->user()->can('update', $vendor) ? route('vendors.show', $vendor->id) : ''}}"
        right_button_text="Edit Vendor" --}}
        >
    </x-page.top>

	<div class="grid max-w-xl grid-cols-4 gap-4 mx-auto lg:max-w-5xl sm:px-6">
        <div class="col-span-4 space-y-4 lg:col-span-2 lg:h-32 lg:sticky lg:top-5">
            {{-- VENDOR DETAILS --}}
            <div class="col-span-4 lg:col-span-2">
                <livewire:vendors.vendor-details :vendor="$vendor">
            </div>

            <livewire:checks.checks-index :vendor="$vendor->id" :view="'vendors.show'" lazy />
        </div>

        {{-- VENDOR TEAM MEMBERS --}}
        @if($vendor->business_type != 'Retail')
            <div class="col-span-4 lg:col-span-2 space-y-4">
                <livewire:users.team-members :vendor="$vendor">

                {{-- INSURANCE --}}
                @if(in_array($vendor->business_type, ["Sub", "DBA"]))
                    <livewire:vendor-docs.vendor-docs-card :vendor="$vendor" :view="true" lazy />
                @endif
            </div>
        @endif
	</div>
    <livewire:vendor-docs.vendor-doc-create />
</div>
