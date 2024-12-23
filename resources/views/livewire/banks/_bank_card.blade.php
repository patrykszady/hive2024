<flux:card class="space-y-2">
    <div class="flex justify-between">
        <div>
            <flux:heading size="lg"><a href="{{route('banks.show', $bank->id)}}">{{$bank->name}}</a></flux:heading>
            <flux:badge color="{{$bank->error == FALSE ? 'green' : 'red'}}">{{$bank->error == FALSE ? 'Connected' : 'Error'}}</flux:badge>
        </div>
        @if(Route::is('banks.show'))
            <flux:button wire:navigate.hover wire:click="plaid_link_token_update" size="sm">Update Bank Account</flux:button>
        @endif
    </div>

    @foreach($bank->accounts as $account)
        <flux:card class="space-y-2">
            <div class="flex justify-between">
                <flux:heading size="lg">{{$account->account_number . ' | ' . $account->type}}</flux:heading>

                <div>
                    <flux:button variant="primary" disabled>
                        @php
                            $balances = collect($bank->plaid_options->accounts)->where('account_id', $account->plaid_account_id)->first();
                        @endphp

                        @if(isset($balances))
                            {{money(isset($balances->balances->available) ? $balances->balances->available : $balances->balances->current)}}
                        @else
                            "N/A"
                        @endif

                    </flux:button>
                    <div><i>{{$bank->updated_at->diffForHumans()}}</i></div>
                </div>
            </div>

            @foreach($account->checks()->whereIn('check_type', ['Transfer', 'Check'])->whereYear('date', '>=', 2024)->whereDoesntHave('transactions')->get() as $check)
                <flux:card>
                    <div class="flex justify-between">
                        <a href="{{route('checks.show', $check->id)}}">
                            <flux:heading>{{$check->owner}}</flux:heading>
                            <flux:subheading>{{$check->check_type . ' ' . $check->check_number . ' ' . $check->date->format('m/d/Y')}}</flux:subheading>
                        </a>
                        <a href="{{route('checks.show', $check->id)}}" class="text-red-800"><b>{{money($check->amount)}}</b></a>
                    </div>
                </flux:card>
            @endforeach
        </flux:card>
    @endforeach
</flux:card>
