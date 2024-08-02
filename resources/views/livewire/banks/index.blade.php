{{-- @section('title','Hive Banks') --}}

<x-cards class="{{$view == NULL ? 'w-full px-4 sm:px-6 lg:max-w-xl lg:px-8 pb-5 mb-1' : ''}}">
    {{-- HEADING --}}
    <x-cards.heading>
        <x-slot name="left">
            <h1>Transaction Accounts</h1>
            <p class="text-sm text-gray-500">Connect your Transactions to automatically match and organize with Expenses and Receipts.</p>
        </x-slot>

        <x-slot name="right">
            <x-cards.button wire:click="plaid_link_token">
                New Account
            </x-cards.button>
        </x-slot>
    </x-cards.heading>

    {{-- SUB-HEADING --}}
    {{-- <x-cards.heading>
        <x-slot name="left">

        </x-slot>
    </x-cards.heading> --}}

    {{-- LIST / using List as a table because of mobile layouts vs a table mobile layout --}}
    <x-cards.body>
        <x-lists.ul>
            @foreach($banks as $bank)
                @php
                    if($bank->error){
                        $line_details = [
                        1 => [
                            'text' => $bank->error,
                            'icon' => 'M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z'

                            ],
                        ];
                    }else{
                        $line_details = '';
                    }
                @endphp

                <x-lists.search_li
                    href="{{route('banks.show', $bank->id)}}"
                    :no_hover=true
                    :line_details="$line_details"
                    :line_title="$bank->name"
                    :bubble_message="$bank->error == FALSE ? 'Connected' : 'Error'"
                    :bubble_color="$bank->error == FALSE ? 'green' : 'red'"
                    >
                </x-lists.search_li>
                <x-lists.ul>
                    @foreach($bank->accounts as $account)
                        @php
                            $balances = collect($bank->plaid_options->accounts)->where('account_id', $account->plaid_account_id)->first();
                            if(isset($balances)){
                                $balance_available = isset($balances->balances->available) ? $balances->balances->available : 25500 - $balances->balances->current;
                            }else{
                                $balance_available = NULL;
                            }

                            $line_details = [
                                1 => [
                                    'text' => $account->account_number,
                                    'icon' => 'M2.5 4A1.5 1.5 0 001 5.5V6h18v-.5A1.5 1.5 0 0017.5 4h-15zM19 8.5H1v6A1.5 1.5 0 002.5 16h15a1.5 1.5 0 001.5-1.5v-6zM3 13.25a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zm4.75-.75a.75.75 0 000 1.5h3.5a.75.75 0 000-1.5h-3.5z'
                                ],
                                2 => [
                                    'text' => $bank->updated_at->diffForHumans(null, null, null, 2),
                                    'icon' => 'M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z'
                                    ],
                                ];
                        @endphp
                        {{-- $this->banks->first()->plaid_options->accounts[0]->balances->available --}}

                        <x-lists.search_li
                            {{-- href="{{route('banks.show', $bank->id)}}" --}}
                            :line_details="$line_details"
                            {{-- :line_title="$bank->name . ' ' . $account->type" --}}
                            :line_title="money($balance_available)"
                            :bubble_message="$account->type"
                            >
                        </x-lists.search_li>
                    @endforeach
                </x-lists.ul>
            @endforeach
        </x-lists.ul>
    </x-cards.body>

    {{-- FOOTER for forms for example --}}
    {{-- <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
        <button type="submit"
            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Save
        </button>
    </div> --}}

    {{-- FOOTER --}}

    {{-- PLAID LINK --}}
    {{-- <meta name="_token" content="{{csrf_token()}}" /> --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('linkToken', exchangeToken => {
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

                        // OLD = @this.dispatch('plaidLinkItem', metadata);
                        @this.dispatch('plaidLinkItem', { item_data: metadata })
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

                    onEvent: function(eventName, metadata) {
                        // Optionally capture Link flow events, streamed through
                        // this callback as your users connect an Item to Plaid.
                        // For example:
                        // eventName = "TRANSITION_VIEW"
                        // metadata  = {
                        //   link_session_id: "123-abc",
                        //   mfa_type:        "questions",
                        //   timestamp:       "2017-09-14T14:42:19.350Z",
                        //   view_name:       "MFA",
                        // }
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
</x-cards>

