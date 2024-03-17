<x-cards.wrapper class="{{$view == NULL ? 'w-full px-4 sm:px-6 lg:max-w-2xl lg:px-8 pb-5 mb-1' : ''}}">
    <x-cards.heading>
        <x-slot name="left">
            <h1>{{$view ? 'Insurance' : $vendor->name}}</h1>
        </x-slot>

        @can('create', App\Models\User::class)
            <x-slot name="right">
                <div class="space-x-2">
                    {{-- if any docs are expired.. policy? --}}
                    @if(isset($vendor->expired_docs))
                        <x-cards.button
                            wire:click="$dispatchTo('vendor-docs.vendor-doc-create', 'requestDocument', { vendor: {{$vendor->id}} })"
                            button_color=red
                            >
                            Request
                        </x-cards.button>
                    @endif

                    <x-cards.button
                        wire:click="$dispatchTo('vendor-docs.vendor-doc-create', 'addDocument', { vendor: {{$vendor->id}} })"
                        :button_color="'white'"
                        >
                        Add
                    </x-cards.button>
                </div>
            </x-slot>
        @endcan
    </x-cards.heading>
    @if(!$vendor_docs->isEmpty())
        <x-lists.ul>
            @foreach($vendor_docs as $doc)
                @php
                    $line_details = [
                        1 => [
                            'text' => $doc->first()->expiration_date->format('m/d/Y'),
                            'icon' => 'M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z'
                            ],
                        2 => [
                            'text' => $doc->first()->number,
                            'icon' => 'M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z'
                            ],
                    ];
                @endphp

                <x-lists.search_li
                    {{-- wire:click="$dispatch('showMember', {{$user->id}})" --}}
                    :line_details="$line_details"
                    :line_title="$doc->first()->type"
                    :bubble_message="$doc->first()->expiration_date > today() ? 'Active' : 'Expired'"
                    :bubble_color="$doc->first()->expiration_date > today() ? 'green' : 'red'"
                    >
                </x-lists.search_li>
            @endforeach
        </x-lists.ul>
    @else
        <p class="text-red-800">NO INSURANCE</p>
    @endif
</x-cards.wrapper>



{{-- <x-cards.wrapper class="{{$view == NULL ? 'w-full px-4 sm:px-6 lg:max-w-2xl lg:px-8 mb-2' : ''}}">
    <x-cards.heading>
        <x-slot name="left">
            <h1>{{$vendor->business_name}}</h1>
        </x-slot>
        <x-slot name="right">
            <div class="space-x-2">
                <x-cards.button
                    wire:click="$dispatchTo('vendor-docs.vendor-doc-create', 'addDocument', { vendor: {{$vendor->id}} })"
                    button_color=white
                    >
                    Add
                </x-cards.button>
                @if(isset($vendor->expired_docs))
                    <x-cards.button
                        wire:click="$dispatchTo('vendor-docs.vendor-doc-create', 'requestDocument', { vendor: {{$vendor->id}} })"
                        button_color=red
                        >
                        Request
                    </x-cards.button>
                @endif
            </div>
        </x-slot>
    </x-cards.heading>

    <x-cards.body>
        <x-lists.ul>
            @foreach($vendor->vendor_docs()->orderBy('expiration_date', 'DESC')->get() as $vendor_doc)
                @php
                    $line_details = [
                        1 => [
                            'text' => $vendor_doc->expiration_date->format('m/d/Y'),
                            'icon' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z'
                            ],
                        2 => [
                            'text' => $vendor_doc->number,
                            'icon' => 'M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z'
                            ],
                        ];
                @endphp

                <x-lists.search_li

                    :line_details="$line_details"
                    :line_title="$vendor_doc->type"
                    :bubble_message="$vendor_doc->expiration_date <= today() ? 'Expired' : 'Active'"
                    :bubble_color="$vendor_doc->expiration_date <= today() ? 'red' : 'green'"
                    >
                </x-lists.search_li>
            @endforeach
        </x-lists.ul>
    </x-cards.body>
</x-cards.wrapper> --}}
