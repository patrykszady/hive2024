<div>
	<x-page.top
        class="lg:max-w-4xl"
        h1="{!! $project->name !!}"
        p="{!! $project->client->name !!}"
		{{-- right_button_href="{{auth()->user()->can('update', $project) ? route('estimates.create', $project->id) : ''}}"
        right_button_text="Create Estimate" --}}
        >
    </x-page.top>

	<div class="grid max-w-xl grid-cols-4 gap-4 mx-auto lg:max-w-5xl sm:px-6">
		{{--  lg:h-32 lg:sticky lg:top-5 --}}
		<div class="col-span-4 space-y-4 lg:col-span-2">
			{{-- PROJECT DETAILS --}}
			<x-cards.wrapper>
				<x-cards.heading>
					<x-slot name="left">
						<h1>Project Details</b></h1>
					</x-slot>

					@can('update', $project)
						<x-slot name="right">
                            {{-- {{route('projects.show', $project->id)}} --}}
							<x-cards.button
                                wire:click="$dispatchTo('projects.project-create', 'editProject', { project: {{$project->id}}})"
                                >
								Edit Project
							</x-cards.button>
						</x-slot>
					@endcan
				</x-cards.heading>
				<x-cards.body>
					<x-lists.ul>
						<x-lists.search_li
                            wire:navigate.hover
							:basic=true
							:line_title="'Project Client'"
							href="{{route('clients.show', $project->client)}}"
							:line_data="$project->client->name"
							>
						</x-lists.search_li>

						<x-lists.search_li
							:basic=true
							:line_title="'Project Name'"
							:line_data="$project->project_name"
							>
						</x-lists.search_li>

						<x-lists.search_li
							:basic=true
							:line_title="'Jobsite Address'"
							href="{{$project->getAddressMapURI()}}"
							:href_target="'blank'"
							:line_data="$project->full_address"
							>
						</x-lists.search_li>

						@can('update', $project)
							<x-lists.search_li
								:basic=true
								:line_title="'Billing Address'"
								:line_data="$project->client->full_address"
								>
							</x-lists.search_li>
						@endcan
					</x-lists.ul>
				</x-cards.body>
			</x-cards.wrapper>

            @can('update', $project)
			    <livewire:project-status.status-create lazy :project="$project"/>
            @endcan

			@if(!$project->expenses->isEmpty())
                <livewire:expenses.expense-index :project="$project->id" :view="'projects.show'"/>
			@endif
		</div>

		@can('update', $project)
            <div class="col-span-4 space-y-4 lg:col-span-2">
                {{-- PROJECT ESTIMATES --}}
                <x-cards.wrapper>
                    <x-cards.heading>
                        <x-slot name="left">
                            <h1>Project Estimates</b></h1>
                        </x-slot>

                        <x-slot name="right">
                            <x-cards.button
                                href="{{route('estimates.create', $project->id)}}"
                                >
                                Create Estimate
                            </x-cards.button>
                        </x-slot>
                    </x-cards.heading>
                    <x-cards.body>
                        <x-lists.ul>
                            @foreach($project->estimates as $estimate)
                                <x-lists.search_li
                                    :basic=true
                                    :line_title="'Estimate ' . $estimate->id"
                                    href="{{route('estimates.show', $estimate->id)}}"
                                    :line_data="money($estimate->estimate_sections->sum('total'))"
                                    >
                                </x-lists.search_li>
                            @endforeach
                        </x-lists.ul>
                    </x-cards.body>
                </x-cards.wrapper>

                @if(in_array($this->project->last_status->title, ['Active', 'Complete',  'Service Call', 'Service Call Complete', 'VIEW ONLY']))
                    {{-- PROJECT FINANCIALS --}}
                    <x-cards.wrapper>
                        <x-cards.heading>
                            <x-slot name="left">
                                <h1>Project Finances</b></h1>
                            </x-slot>

                            <x-slot name="right">
                                <x-cards.button
                                    wire:click="$dispatchTo('bids.bid-create', 'addBids', { vendor: {{auth()->user()->vendor->id}}, project: {{$project->id}} })"
                                    {{-- wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50" --}}
                                    >
                                    Edit Bid
                                </x-cards.button>
                            </x-slot>
                            <livewire:bids.bid-create />
                        </x-cards.heading>

                        <x-cards.body>
                            {{-- wire:loading should just target the Reimbursment search_li not the entire Proejct Finances wrapper--}}
                            <x-lists.ul
                                wire:target="print_reimbursements"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 text-opacity-40"
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
                                    wire:click="print_reimbursements"
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
                    </x-cards.wrapper>

                    {{-- PROJECT DISTRIBUTIONS --}}
                    @if(!$this->project->distributions->isEmpty())
                        <x-cards.wrapper>
                            <x-cards.heading>
                                <x-slot name="left">
                                    <h1>Project Distributions</b></h1>
                                </x-slot>
                            </x-cards.heading>
                            <x-cards.body>
                                <x-lists.ul>
                                    @foreach($this->project->distributions as $distribution)
                                        <x-lists.search_li
                                            :basic=true
                                            :line_title="$distribution->name"
                                            :line_data="money($distribution->pivot->amount) . ' | ' . $distribution->pivot->percent . '%'"
                                            >
                                        </x-lists.search_li>
                                    @endforeach
                                </x-lists.ul>
                            </x-cards.body>
                        </x-cards.wrapper>
                    @endif

                    {{-- PROJECT PAYMENTS --}}
                    <x-cards.wrapper class="col-span-4 lg:col-span-2 lg:col-start-3">
                        <x-cards.heading>
                            <x-slot name="left">
                                <h1>Payments</b></h1>
                            </x-slot>

                            @can('create', App\Models\Payment::class)
                                <x-slot name="right">
                                    {{-- 12-09-22 modal not page reload --}}
                                    {{-- wire:navigate.hover --}}
                                    <x-cards.button href="{{route('payments.create', $project->client->id)}}">
                                        Add Payment
                                    </x-cards.button>
                                </x-slot>
                            @endcan
                        </x-cards.heading>

                        @if(!$project->payments->isEmpty())
                            <x-lists.ul>
                                @foreach($project->payments()->orderBy('date', 'DESC')->get() as $payment)
                                    @php
                                        $line_details = [
                                            1 => [
                                                'text' => $payment->date->format('m/d/Y'),
                                                'icon' => 'M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z'
                                                ],
                                            2 => [
                                                'text' => $payment->reference,
                                                'icon' => 'M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z'
                                                ],
                                            ];
                                    @endphp

                                    <x-lists.search_li
                                        href=""
                                        :line_details="$line_details"
                                        :line_title="money($payment->amount)"
                                        :bubble_message="$payment->transaction ? 'Complete' : 'No Transaction'"
                                        :bubble_color="$payment->transaction ? 'green' : 'red'"
                                        >
                                    </x-lists.search_li>
                                @endforeach
                            </x-lists.ul>
                        @endif
                    </x-cards.wrapper>
                @endif
            </div>
		@endcan
	</div>

    <livewire:projects.project-create />
</div>

