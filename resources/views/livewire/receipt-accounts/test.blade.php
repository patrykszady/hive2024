<x-modals.modal>
    <form wire:submit="store">
        <x-cards.heading>
            <x-slot name="left">
                <h1>{{$vendor ? $vendor->name : 'NO VENDOR'}}</h1>
            </x-slot>
        </x-cards.heading>
        <x-cards.body :class="'space-y-4 my-4'">
            <x-forms.row
                wire:model.live="distribution_id"
                errorName="distribution_id"
                name="distribution_id"
                text="Distribution"
                type="dropdown"
                >

                <option
                    value=""
                    readonly
                    x-text="'CONNECT'"
                    >
                </option>
                <option
                    value="NO_PROJECT"
                    x-text="'NO PROJECT'"
                    >
                </option>

                @foreach($distributions as $distribution)
                    <option
                        value="{{$distribution->id}}"
                        >
                        {{$distribution->name}}
                    </option>
                @endforeach
            </x-forms.row>

            @if($vendor ? $vendor->receipts->first()->from_type == 4 : false)
                <div
                    x-data="{ logged_in: @entangle('vendor.logged_in') }">
                    <x-forms.row
                        wire:click="api_login"
                        name=""
                        text=""
                        type="button"
                        x-text="logged_in == true ? 'Logout' : 'Login'"
                        >
                    </x-forms.row>
                </div>
            @endif
        </x-cards.body>
        <x-cards.footer>
            <div
                x-data="{ open: @entangle('distribution_id') }"
                x-show="open"
                x-transition
                >
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm disabled:opacity-50 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                    Save
                </button>
            </div>
        </x-cards.footer>
    </form>
</x-modals.modal>
