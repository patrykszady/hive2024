<x-modal wire:model="showModal">
    <x-modal.panel>
        <form wire:submit="{{$view_text['form_submit']}}">
            <x-cards.heading>
                <x-slot name="left">
                    <h1>
                        {{$view_text['card_title']}}
                    </h1>
                </x-slot>
            </x-cards.heading>

            <x-cards.body :class="'space-y-2 my-4'">
                {{-- CLIENT ID --}}
                <div
                    x-data="{ existing_client: @entangle('existing_client') }"
                    >
                    <x-forms.row
                        wire:model.live="form.client_id"
                        errorName="form.client_id"
                        name="client_id"
                        text="Client"
                        type="dropdown"
                        {{-- :disabled="isset($view) ? true : false " --}}
                        {{-- x-bind:disabled="!vendor_id_disabled || business_type_disabled == '1099'" --}}
                        x-bind:disabled="existing_client"
                        >

                        <option value="" readonly>Select Client</option>
                        @foreach ($clients as $client)
                            <option value="{{$client->id}}">{{$client->name}}</option>
                        @endforeach
                    </x-forms.row>
                </div>

                {{-- PROJECT NAME --}}
                <x-forms.row
                    wire:model="form.project_name"
                    errorName="form.project_name"
                    name="project_name"
                    text="Project Name"
                    >
                </x-forms.row>

                {{-- ADDRESS --}}
                <div
                    x-data="{ client: @entangle('form.client_id') }"
                    x-show="client"
                    x-transition
                    class="my-4 space-y-4"
                    >

                    <x-forms.row
                        wire:model.live="form.project_existing_address"
                        errorName="form.project_existing_address"
                        name="project_existing_address"
                        text="Address"
                        type="dropdown"
                        {{-- :disabled="isset($client) ? isset($client['id']) ? true : false : false" --}}
                        {{-- x-bind:disabled="!vendor_id_disabled || business_type_disabled == '1099'" --}}
                        >
                        <option value="" readonly>Select Address</option>
                        @foreach($client_addresses as $project_address)
                            @if(isset($project_address->id))
                                <option value="{{$project_address->id}}" selected>{{$project_address->address}}</option>
                            @else
                                <option value="CLIENT_PROJECT" selected>{{$project_address['address']}}</option>
                            @endif
                        @endforeach
                        <option value="NEW" readonly>New Address</option>
                    </x-forms.row>

                    {{-- only show if new address --}}
                    <div
                        x-data="{ new_address: @entangle('form.project_existing_address') }"
                        x-show="new_address == 'NEW'"
                        x-transition
                        class="my-4 space-y-4"
                        >
                        @include('components.forms._address_form', ['model' => 'form'])
                    </div>
                </div>
            </x-cards.body>

            <x-cards.footer>
                <button
                    x-on:click="open = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button>
                <x-forms.button
                    type="submit"
                    >
                    {{$view_text['button_text']}}
                </x-forms.button>
            </x-cards.footer>
        </form>
    </x-modal.panel>
</x-modal>
