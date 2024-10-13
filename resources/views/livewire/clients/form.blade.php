<flux:modal name="client_form_modal" class="space-y-2 min-w-2xl">
    <div class="flex justify-between">
        <flux:heading size="lg">{{$view_text['card_title']}}</flux:heading>
    </div>

    <flux:separator variant="subtle" />

    <form wire:submit="{{$view_text['form_submit']}}" class="grid gap-6">
        <flux:input
            wire:model.live.debounce.500ms="form.client_name"
            disabled
            label="Client User"
            type="text"
        />
        {{-- <div
            x-data="{ open: @entangle('client_name')}"
            x-show="open"
            x-transition
            class="my-4 space-y-4"
            >
            <flux:input
                wire:model="client_name"
                disabled
                label="Client Name"
                type="text"
            />
        </div> --}}

        <div
            x-data="{ open: @entangle('client_name'), address: @entangle('form.address')}"
            x-show="!open && !address"
            x-transition
            >
            <flux:radio.group wire:model.live="user_client_id" label="Existing Clients" class="space-y-4">
                @if($user_clients)
                    @foreach ($user_clients as $client)
                        <flux:card class="space-y-6">
                            <flux:radio
                                name="clients"
                                value="{{$client->id}}"
                                label="{{$client->address}}"
                                description="{!!$client->name!!}"
                                {{-- @if($loop->first)
                                    checked
                                @endif --}}
                            />
                        </flux:card>
                    @endforeach
                @endif

                <flux:card class="space-y-6">
                    <flux:radio name="clients" value="NEW" label="New Client" />
                </flux:card>
            </flux:radio.group>

            <flux:separator variant="subtle" />
        </div>

        <div
            x-data="{open: @entangle('user_client_id'), address: @entangle('form.address')}"
            x-show="open == 'NEW' || address"
            x-transition
            class="space-y-4"
            >

            <flux:input
                wire:model.live.debounce.500ms="form.business_name"
                label="Business Name"
                placeholder="Business Name"
                type="text"
            />

            {{-- ADDRESS --}}
            @include('components.forms._address_form')

            <flux:input
                wire:model.live.debounce.500ms="form.source"
                label="Referral"
                type="text"
                placeholder="Referral / Lead / Source"
            />
        </div>

        <div class="flex space-x-2 sticky bottom-0">
            <flux:spacer />

            <flux:button type="submit" variant="primary">{{$view_text['button_text']}}</flux:button>
        </div>
    </form>
</flux:modal>
