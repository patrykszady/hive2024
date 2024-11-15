<x-cards class="max-w-lg mx-auto">
    {{-- HEADING --}}
    <x-cards.heading>
        <x-slot name="left">
            <h1>{{$bank->name}}</h1>
        </x-slot>

        <x-slot name="right">
            <x-cards.button wire:click="plaid_link_token_update">
                Update Bank
            </x-cards.button>
        </x-slot>
        @if($bank->plaid_options->error)
            <b><span class="text-red-900"><i>{{$bank->plaid_options->error->error_code}}</i></span></b>
        @endif
    </x-cards.heading>

    <x-lists.ul>
        @foreach($bank->accounts as $account)
            @php
                $balances = collect($bank->plaid_options->accounts)->where('account_id', $account->plaid_account_id)->first();
                if(isset($balances)){
                    $balance_available = money(isset($balances->balances->available) ? $balances->balances->available : $balances->balances->current);
                }else{
                    $balance_available = "N/A";
                }
                $line_details = [
                    1 => [
                        'text' => $account->account_number,
                        'icon' => 'M2.5 4A1.5 1.5 0 001 5.5V6h18v-.5A1.5 1.5 0 0017.5 4h-15zM19 8.5H1v6A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-6zM3 13.25a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zm4.75-.75a.75.75 0 000 1.5h3.5a.75.75 0 000-1.5h-3.5z'
                    ],
                    2 => [
                        'text' => $bank->updated_at->diffForHumans(),
                        'icon' => 'M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z'
                        ],
                    ];
            @endphp

            <x-lists.search_li
                :line_details="$line_details"
                :line_title="$balance_available"
                :bubble_message="$account->type"
                >
            </x-lists.search_li>

            @foreach($account->checks()->whereIn('check_type', ['Transfer', 'Check'])->whereYear('date', '>=', 2024)->whereDoesntHave('transactions')->get() as $check)
                <x-lists.search_li
                    href="{{route('checks.show', $check->id)}}"
                    :href_target="'blank'"
                    :basic=true
                    :line_title="money($check->amount)"
                    :line_data="$check->owner"
                    >
                </x-lists.search_li>
            @endforeach
        @endforeach
    </x-lists.ul>

    {{-- PLAID LINK --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('linkTokenUpdate', exchangeToken => {
                var handler = Plaid.create({
                    token: exchangeToken,

                    onLoad: function() {
                        handler.open();
                    },

                    onSuccess: function(token, metadata) {
                        // console.log(metadata);
                        // Send the public_token to your app server.
                        // The metadata object contains info about the institution the
                        // user selected and the account ID or IDs, if the
                        // Select Account view is enabled.

                        // OLD Livewire.emit('plaidLinkItemUpdate', metadata);
                        //plaidLinkItemUpdate = plaid_link_item on BankIndex
                        @this.dispatch('plaidLinkItemUpdate', { item_data: metadata })
                    },

                    onExit: function(err, metadata) {
                        // The user exited the Link flow or error above.
                        if (err != null) {
                            // The user encountered a Plaid API error prior to exiting.
                        }
                            // metadata contains information about the institution
                            // that the user selected and the most recent API request IDs.
                            // Storing this information can be helpful for support.
                    },

                    // onEvent: function(eventName, metadata) {
                    //     // Optionally capture Link flow events, streamed through
                    //     // this callback as your users connect an Item to Plaid.
                    //     // For example:
                    //     // eventName = "TRANSITION_VIEW"
                    //     // metadata  = {
                    //     //   link_session_id: "123-abc",
                    //     //   mfa_type:        "questions",
                    //     //   timestamp:       "2017-09-14T14:42:19.350Z",
                    //     //   view_name:       "MFA",
                    //     // }
                    // }
                });
            });
        });
    </script>
    <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
</x-cards>

