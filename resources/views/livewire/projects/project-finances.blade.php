{{-- PROJECT FINANCIALS --}}
<x-cards>
    <x-cards.heading>
        <x-slot name="left">
            <h1>Project Finances</b></h1>
        </x-slot>

        <x-slot name="right">
            <x-cards.button
                wire:click="$dispatchTo('bids.bid-create', 'addBids', { vendor: {{auth()->user()->vendor->id}}, project: {{$project->id}} })"
                >
                Edit Bid
            </x-cards.button>
        </x-slot>
        <livewire:bids.bid-create />
    </x-cards.heading>

    <x-cards.body>
        {{-- wire:loading should just target the Reimbursment search_li not the entire Proejct Finances card--}}
        <x-lists.ul
            {{-- wire:target="print_reimbursements"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50 text-opacity-40" --}}
            >
            <x-lists.search_li
                :basic=true
                :line_title="'Estimate'"
                :line_data="money($finances['estimate'])"
                >
            </x-lists.search_li>

            <x-lists.search_li
                :basic=true
                :line_title="'Change Order'"
                :line_data="money($finances['change_orders'])"
                >
            </x-lists.search_li>

            <x-lists.search_li
                wire:click="$dispatchTo('projects.project-show', 'print_reimbursements')"
                :basic=true
                :line_title="'Reimbursements'"
                :line_data="money($finances['reimbursments'])"
                >
            </x-lists.search_li>

            <x-lists.search_li
                :basic=true
                :bold="TRUE"
                {{-- make gray --}}
                :line_title="'TOTAL PROJECT'"
                :line_data="money($finances['total_project'])"
                >
            </x-lists.search_li>

            <x-lists.search_li
                :basic=true
                :line_title="'Expenses'"
                :line_data="money($finances['expenses'])"
                >
            </x-lists.search_li>

            <x-lists.search_li
                :basic=true
                :line_title="'Timesheets'"
                :line_data="money($finances['timesheets'])"
                >
            </x-lists.search_li>

            <x-lists.search_li
                :basic=true
                :bold="TRUE"
                {{-- make gray --}}
                :line_title="'TOTAL COST'"
                :line_data="money($finances['total_cost'])"
                >
            </x-lists.search_li>

            <x-lists.search_li
                :basic=true
                :line_title="'Payments'"
                :line_data="money($finances['payments'])"
                >
            </x-lists.search_li>

            @if(in_array($this->project->last_status->title, ['Complete',  'Service Call', 'Service Call Complete']))
                <x-lists.search_li
                    :basic=true
                    :bold="TRUE"
                    :line_title="'PROFIT'"
                    :line_data="money($finances['profit'])"
                    >
                </x-lists.search_li>
            @endif

            <x-lists.search_li
                :basic=true
                {{-- make gray --}}
                :line_title="'Balance'"
                :line_data="money($finances['balance'])"
                >
            </x-lists.search_li>
        </x-lists.ul>
    </x-cards.body>
</x-cards>
