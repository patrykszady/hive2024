<div>
    <x-cards.heading>
        <div class="mx-auto">
            <div>
                <select
                    wire:model.live="bank_plaid_ins_id"
                    id="bank_plaid_ins_id"
                    name="bank_plaid_ins_id"
                    class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="" readonly>All Banks</option>
                    @foreach($banks as $institution_id => $bank)
                        <option value="{{$institution_id}}">{{$bank->first()->name}}</option>
                    @endforeach
                </select>
            </div>
            @if(!empty($bank_owners))
                <div>
                    <select
                        wire:model.live="bank_owner"
                        id="bank_owner"
                        name="bank_owner"
                        class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="" readonly>All Owners</option>
                        @foreach($bank_owners as $owner)
                            <option value="{{$owner}}">{{$owner}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </x-cards.heading>
</div>
