<div>
	<div class="grid max-w-xl grid-cols-4 gap-4 lg:max-w-5xl sm:px-6">
		{{--  lg:h-32 lg:sticky lg:top-5 --}}
		<div class="col-span-4 lg:col-span-2 space-y-4">
			{{-- PROJECT DETAILS --}}
            <x-lists.details_card>
                {{-- HEADING --}}
                <x-slot:heading>
                    <div>
                        <flux:heading size="lg" class="mb-0">Project Details</flux:heading>
                    </div>

                    @can('update', $project)
                        <flux:button
                            wire:click="$dispatchTo('projects.project-create', 'editProject', { project: {{$project->id}}})"
                            size="sm"
                            >
                            Edit Project
                        </flux:button>
                    @endcan
                </x-slot>

                {{-- DETAILS --}}
                <x-lists.details_list>
                    <x-lists.details_item title="Project Client" detail="{{$project->client->name}}" href="{{route('clients.show', $project->client)}}" />
                    <x-lists.details_item title="Project Name" detail="{!! $project->project_name !!}" />
                    <x-lists.details_item title="Jobsite Address" detail="{!!$project->full_address!!}" href="{{$project->getAddressMapURI()}}" target="_blank" />
                    @can('update', $project)
                        <x-lists.details_item title="Billing Address" detail="{!!$project->client->full_address!!}" />
                        {{-- @if($project->belongs_to_vendor_id == auth()->user()->vendor->id)
                            <x-lists.search_li
                                :basic=true
                                :line_title="'Invite Contractors'"
                                :line_data="'Choose Vendors'"
                                :button_wire="TRUE"
                                wire:click="$dispatchTo('projects.project-vendors', 'addVendors')"
                                >
                            </x-lists.search_li>

                            <livewire:projects.project-vendors :project="$project"/>
                        @endif --}}
                    @endcan
                </x-lists.details_list>
            </x-lists.details_card>
		</div>

        @can('update', $project)
            <div class="col-span-4 space-y-4 lg:col-span-2 lg:col-start-3">
                {{-- PROJECT ESTIMATES --}}
                <x-cards>
                    <x-cards.heading>
                        <x-slot name="left">
                            <h1>Project Estimates</b></h1>
                        </x-slot>

                        @can('create', [App\Models\Estimate::class, $project])
                            <x-slot name="right">
                                <x-cards.button
                                    href="{{route('estimates.create', $project->id)}}"
                                    {{-- wire:click="$dispatch('estimate.create')" --}}
                                    >
                                    Create Estimate
                                </x-cards.button>
                            </x-slot>
                            {{-- <livewire:estimates.estimate-create :project="$project" /> --}}
                        @endcan
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
                </x-cards>

                <livewire:project-status.status-create :project="$project" lazy />
            </div>
        @endcan

        @can('update', $project)
            {{-- @if($project->tasks->count() != 0)
                <div class="col-span-4 space-y-4">
                    <livewire:tasks.planner :single_project_id="$project->id" />
                </div>
            @endif --}}

            <div class="col-span-4 space-y-4 lg:col-span-2">
                @if(!$project->expenses->isEmpty())
                    <livewire:expenses.expense-index :project="$project->id" :view="'projects.show'"/>
                @endif
            </div>
        @endcan

		@can('update', $project)
            <div class="col-span-4 space-y-4 lg:col-span-2 lg:col-start-3">
                @if(in_array($this->project->last_status->title, ['Active', 'Complete',  'Service Call', 'Service Call Complete', 'VIEW ONLY']))
                    {{-- PROJECT FINANCIALS --}}
                    <livewire:projects.project-finances :project="$project" lazy />

                    {{-- PROJECT DISTRIBUTIONS --}}
                    @if(!$this->project->distributions->isEmpty())
                        <x-cards>
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
                        </x-cards>
                    @endif

                    {{-- PROJECT PAYMENTS --}}
                    <livewire:payments.payments-index :project="$project" :view="'projects.show'" />
                @endif
            </div>
		@endcan
	</div>

    <livewire:projects.project-create />
</div>
