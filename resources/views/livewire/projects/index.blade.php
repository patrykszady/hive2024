<div class="max-w-3xl">
    @if($view === NULL)
        <flux:card class="space-y-2 mb-4">
            <div class="flex justify-between">
                <flux:heading size="lg">Filters</flux:heading>
                @can('create', App\Models\Project::class)
                    <flux:button wire:click="$dispatchTo('projects.project-create', 'newProject', { client_id: '{{$client_id}}' })">Create Project</flux:button>
                @endcan
            </div>
            {{-- NEW PROJECT MODAL --}}
            <livewire:projects.project-create :$clients />
            <flux:separator variant="subtle" />

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <flux:input wire:model.live="project_name_search" label="Project" icon="magnifying-glass" placeholder="Search projects..." />

                <flux:select wire:model.live="client_id" label="Client" variant="listbox" searchable placeholder="All Clients...">
                    <x-slot name="search">
                        <flux:select.search placeholder="Search..." />
                    </x-slot>
                    @foreach ($clients as $client)
                        <flux:option value="{{$client->id}}">{{ $client->name }}</flux:option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="project_status_title" label="Status" placeholder="Status...">
                    @include('livewire.projects._status_options')
                </flux:select>
            </div>
        </flux:card>
    @endif

    <flux:card class="space-y-2" lazy>
        <div>
            <flux:heading size="lg">Projects</flux:heading>
        </div>

        <div class="space-y-2">
            <flux:table :paginate="$this->projects">
                <flux:columns>
                    <flux:column>Address</flux:column>
                    <flux:column>Client</flux:column>
                    <flux:column>Name</flux:column>
                    {{-- <flux:column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection" wire:click="sort('date')">Date</flux:column>
                    @if($view != 'checks.show')
                        <flux:column >Vendor</flux:column>
                    @endif

                    @if($view != 'projects.show')
                        <flux:column>Project</flux:column>
                    @endif --}}
                    <flux:column>Status</flux:column>
                </flux:columns>

                <flux:rows>
                    @foreach ($this->projects as $project)
                        <flux:row :key="$project->id">
                            <flux:cell
                                wire:navigate.hover
                                href="{{route('projects.show', $project->id)}}"
                                variant="strong"
                                class="cursor-pointer"
                                >
                                {{ $project->address }}
                            </flux:cell>
                            <flux:cell>{{ $project->client->name }}</flux:cell>
                            <flux:cell>{{ $project->project_name }}</flux:cell>
                            {{-- <flux:cell
                                wire:click="$dispatchTo('projects.expense-create', 'editExpense', { expense: {{$project->id}}})"
                                variant="strong"
                                class="cursor-pointer"
                                >
                                {{ $project->address }}
                            </flux:cell>
                            <flux:cell>{{ $expense->date->format('m/d/Y') }}</flux:cell>
                            @if($view != 'checks.show')
                                <flux:cell><a wire:navigate.hover href="{{route('vendors.show', $expense->vendor->id)}}">{{Str::limit($expense->vendor->name, 20)}}</a></flux:cell>
                            @endif
                            @if($view != 'projects.show')
                                <flux:cell>{{ Str::limit($expense->project->name, 25) }}</flux:cell>
                            @endif --}}
                            <flux:cell>
                                <flux:badge size="sm" :color="$project->last_status->title == 'Complete' ? 'green' : ($project->last_status->title == 'Active' ? 'blue' : 'yellow')" inset="top bottom">{{ $project->last_status->title }}</flux:badge>
                            </flux:cell>
                        </flux:row>
                    @endforeach
                </flux:rows>
            </flux:table>
        </div>
    </flux:card>

    {{-- <livewire:expenses.expense-create /> --}}
</div>
