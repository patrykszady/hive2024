<flux:modal name="lead_form_modal" class="space-y-2">
    <div class="flex justify-between">
        <flux:heading size="lg">Lead</flux:heading>
    </div>

    <flux:separator variant="subtle" />

    <flux:tab.group>
        <flux:tabs wire:model="tab">
            <flux:tab name="details">Details</flux:tab>
            <flux:tab name="messages">Mesages</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="details">

            <form wire:submit="{{$view_text['form_submit']}}" class="grid gap-6">
                <flux:textarea
                    wire:model.live="lead.message"
                    disabled
                    label="Message"
                    rows="auto"
                    resize="none"
                />

                <flux:input
                    wire:model.live="date"
                    disabled
                    label="Date"
                    type="date"
                />

                <flux:input
                    wire:model.live="lead.origin"
                    disabled
                    label="Origin"
                    type="text"
                />

                <flux:input
                    wire:model.live="full_name"
                    disabled
                    label="User"
                    type="text"
                    placeholder="Create User"
                />

                <flux:input
                    wire:model.live="lead.address"
                    label="Address"
                    type="text"
                    placeholder="Address"
                />

                {{--  id="new_project_id"  --}}
                <flux:select wire:model.live="lead_status" label="Status" variant="listbox" placeholder="Choose Status...">
                    @include('livewire.leads._lead_status_options')
                </flux:select>

                <flux:textarea
                    wire:model.live="lead.notes"
                    label="Notes"
                    rows="auto"
                    resize="none"
                />

                <div class="flex space-x-2 sticky bottom-0">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">{{$view_text['button_text']}}</flux:button>
                </div>
            </form>
        </flux:tab.panel>
        <flux:tab.panel name="messages">...</flux:tab.panel>
    </flux:tab.group>
</flux:modal>
