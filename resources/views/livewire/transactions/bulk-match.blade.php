<div>
    <x-cards class="w-full px-4 pb-5 mb-1 sm:px-6 lg:max-w-2xl lg:px-8">
        {{-- HEADING --}}
        <x-cards.heading>
            <x-slot name="left">
                <h1>Transaction / Vendor Bulk Adds</h1>
                <p
                    class="max-w-2xl mt-1 text-sm text-gray-500"
                    >
                    Bulk Match Transactions for Retail Vendors. Manual Match Below.
                </p>
            </x-slot>
            <x-slot name="right">
                <x-cards.button
                    wire:click="$dispatchTo('bulk-match.bulk-match-create', 'newMatch')"
                    >
                    New
                </x-cards.button>
            </x-slot>
        </x-cards.heading>

        {{-- BODY --}}
        <x-cards.body>
            <div>
                <x-lists.ul>
                    @foreach($bulk_matches as $match)
                        @php
                            $line_details = [
                                1 => [
                                    'text' => $match->vendor->business_name,
                                    'icon' => 'M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z'
                                    ],
                                2 => [
                                    'text' => $match->distribution ? $match->distribution->name : 'SPLIT',
                                    'icon' => 'M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z'
                                    ],
                                3 => [
                                    'text' => $match->amount != NULL ? $match->options['amount_type'] . $match->amount : 'Any Amount',
                                    'icon' => 'M10.75 10.818v2.614A3.13 3.13 0 0011.888 13c.482-.315.612-.648.612-.875 0-.227-.13-.56-.612-.875a3.13 3.13 0 00-1.138-.432zM8.33 8.62c.053.055.115.11.184.164.208.16.46.284.736.363V6.603a2.45 2.45 0 00-.35.13c-.14.065-.27.143-.386.233-.377.292-.514.627-.514.909 0 .184.058.39.202.592.037.051.08.102.128.152z M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-6a.75.75 0 01.75.75v.316a3.78 3.78 0 011.653.713c.426.33.744.74.925 1.2a.75.75 0 01-1.395.55 1.35 1.35 0 00-.447-.563 2.187 2.187 0 00-.736-.363V9.3c.698.093 1.383.32 1.959.696.787.514 1.29 1.27 1.29 2.13 0 .86-.504 1.616-1.29 2.13-.576.377-1.261.603-1.96.696v.299a.75.75 0 11-1.5 0v-.3c-.697-.092-1.382-.318-1.958-.695-.482-.315-.857-.717-1.078-1.188a.75.75 0 111.359-.636c.08.173.245.376.54.569.313.205.706.353 1.138.432v-2.748a3.782 3.782 0 01-1.653-.713C6.9 9.433 6.5 8.681 6.5 7.875c0-.805.4-1.558 1.097-2.096a3.78 3.78 0 011.653-.713V4.75A.75.75 0 0110 4z'
                                    ],
                                ];

                            if(isset($match->options['desc'])){
                                $line_details[4] =
                                    [
                                    'text' => $match->options['desc'],
                                    'icon' => 'M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z'
                                    ];
                            }
                        @endphp

                        <x-lists.search_li
                            wire:click="$dispatchTo('bulk-match.bulk-match-create', 'updateMatch', { match: {{$match->id}} })"
                            :line_details="$line_details"
                            :line_title="$match->vendor->business_name"
                            :bubble_message="'Automatic Match'"
                            >
                        </x-lists.search_li>
                    @endforeach
                </x-lists.ul>
            </div>
        </x-cards.body>

        <livewire:bulk-match.bulk-match-create :distributions="$distributions" :vendors="$bulk_matches->unique('vendor.id')->pluck('vendor.id')"/>
    </x-cards>
</div>
