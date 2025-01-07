<flux:modal name="bulk_match_form_modal" class="space-y-2">
    <div class="flex justify-between">
        <flux:heading size="lg">{{$view_text['card_title']}}</flux:heading>
    </div>

    <flux:separator variant="subtle" />

    <form wire:submit="{{$view_text['form_submit']}}" class="grid gap-6">
        {{-- VENDOR --}}
        <flux:field>
            {{-- $view_text['form_submit'] === 'edit' ? $new_vendor->name : 'Choose vendor...' --}}
            <flux:select label="Vendor" wire:model.live="form.vendor_id" variant="listbox" searchable placeholder="{{'Choose vendor...'}}">
                <x-slot name="search">
                    <flux:select.search placeholder="Search..." />
                </x-slot>
                {{-- existing_vendors --}}
                @foreach($this->new_vendors as $vendor)
                    <flux:option value="{{$vendor->id}}">{{$vendor->name}}</flux:option>
                @endforeach
            </flux:select>
        </flux:field>
    </form>
</flux:modal>
