<div class="grid grid-cols-5 gap-4 xl:relative sm:px-6 lg:max-w-7xl">
    <div class="col-span-5 space-y-4 lg:col-span-2 lg:h-32 lg:sticky lg:top-5">
        {{-- ESTIMATE DETAILS --}}
        <x-lists.details_card>
            {{-- HEADING --}}
            <x-slot:heading>
                <flux:heading size="lg" class="mb-0">Estimate Details</flux:heading>
                <flux:dropdown>
                    <flux:button size="sm" icon-trailing="chevron-down">Options</flux:button>

                    <flux:menu>
                        <flux:menu.item wire:click="$dispatchTo('estimates.estimate-accept', 'accept')">Finalize Estimate</flux:menu.item>
                        <flux:menu.item wire:click="$dispatchTo('estimates.estimate-duplicate', 'duplicateModal', { estimate: {{$estimate->id}} })">Duplicate Estimate</flux:menu.item>
                        <flux:menu.item wire:click="$dispatchTo('estimates.estimate-combine', 'combineModal', { existing_estimate_id: {{$estimate->id}} })">Combine Estimate</flux:menu.item>

                        <flux:menu.separator />

                        <flux:menu.item wire:click="print('estimate')">Export Estimate</flux:menu.item>
                        <flux:menu.item wire:click="print('invoice')">Export Invoice</flux:menu.item>
                        <flux:menu.item wire:click="print('work order')">Export Work Order</flux:menu.item>

                        <flux:menu.separator />

                        <flux:menu.item wire:click="delete" variant="danger">Delete</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </x-slot>

            {{-- <livewire:estimates.estimate-accept :estimate="$estimate"/>
            <livewire:estimates.estimate-duplicate />
            <livewire:estimates.estimate-combine :client="$estimate->client"/> --}}

            {{-- DETAILS --}}
            <x-lists.details_list>
                <x-lists.details_item title="Client" detail="{{$estimate->client->name}}" href="{{route('clients.show', $estimate->client->id)}}" />
                <x-lists.details_item title="Project Name" detail="{{$estimate->project->project_name}}" href="{{route('projects.show', $estimate->project->id)}}" />
                <x-lists.details_item title="Jobsite Address" detail="{!!$estimate->project->full_address!!}" href="{{$estimate->project->getAddressMapURI()}}" target="_blank" />
                <x-lists.details_item title="Estimate Number" detail="{{$estimate->number}}" />
            </x-lists.details_list>
        </x-lists.details_card>

        {{-- PAYMENT SCHEDULE --}}
        <flux:card class="space-y-2">
            <flux:accordion transition>
                <flux:accordion.item>
                    <flux:accordion.heading>
                        <flux:heading size="lg">Payment Schedule</flux:heading>
                    </flux:accordion.heading>

                    <flux:accordion.content>
                        <flux:separator variant="subtle" />
                        <x-lists.ul>
                            @foreach($estimate->payments as $payment)
                                <x-lists.search_li
                                    :basic=true
                                    :line_title="$payment['description']"
                                    :line_data="$loop->last && $payment['amount'] == '' ? 'Balance' : money($payment['amount'])"
                                    >
                                </x-lists.search_li>
                            @endforeach
                        </x-lists.ul>
                    </flux:accordion.content>
                </flux:accordion.item>
            </flux:accordion>
        </flux:card>

        {{-- PROJECT FIANCES --}}
        <livewire:projects.project-finances :project="$estimate->project" lazy />
    </div>

    <div class="col-span-5 space-y-2 lg:col-span-3 lg:col-start-3 overflow-y-auto">
        {{-- SECTIONS --}}
        @foreach($sections as $index => $section)
            <flux:card class="space-y-2">
                <flux:accordion transition>
                    <flux:accordion.item>
                        <flux:accordion.heading>
                            <flux:heading size="lg" class="mb-0">{{$section->name}}</flux:heading>
                        </flux:accordion.heading>

                        <flux:accordion.content>
                            <flux:separator variant="subtle"/>
                                <flux:table>
                                    <flux:columns>
                                        <flux:column class="w-6"></flux:column>
                                        <flux:column class="w-1/3">Item</flux:column>
                                        <flux:column>Quantity</flux:column>
                                        <flux:column>Unit</flux:column>
                                        <flux:column>Cost</flux:column>
                                        <flux:column>Total</flux:column>
                                    </flux:columns>

                                    <flux:rows>
                                        @foreach($section->estimate_line_items as $line_item)
                                            <flux:row :key="$line_item->id">
                                                <flux:cell>{{$index + 1}}.{{$line_item->section_index}}</flux:cell>
                                                <flux:cell variant="strong">
                                                    <a
                                                        class="cursor-pointer"
                                                        wire:click="$dispatchTo('line-items.estimate-line-item-create', 'editOnEstimate', { estimate_line_item_id: {{$line_item->id}} })"
                                                        >
                                                        <b>{{$line_item->name}}</b>
                                                    </a>
                                                    <br>
                                                    <i>{{$line_item->category}}/{{$line_item->sub_category}}</i>
                                                </flux:cell>
                                                <flux:cell>{{$line_item->unit_type !== 'no_unit' ? $line_item->quantity : ''}}</flux:cell>
                                                <flux:cell>{{$line_item->unit_type !== 'no_unit' ? $line_item->unit_type : ''}}</flux:cell>
                                                <flux:cell>{{$line_item->unit_type !== 'no_unit' ? money($line_item->cost) : ''}}</flux:cell>
                                                <flux:cell variant="strong">{{money($line_item->total)}}</flux:cell>
                                            </flux:row>
                                            <flux:row class="w-full">
                                                <flux:cell></flux:cell>
                                                <flux:cell>
                                                    <div class="w-48">
                                                        <p>{!! $line_item->desc !!}</p>
                                                        <p><i>{!! $line_item->notes !!}</i></p>
                                                    </div>
                                                </flux:cell>
                                            </flux:row>
                                        @endforeach
                                    </flux:rows>
                                </flux:table>
                        </flux:accordion.content>
                    </flux:accordion.item>
                </flux:accordion>

                {{-- FOOTER --}}
                <flux:separator variant="subtle"/>
                <div class="flex justify-between">
                    <flux:button
                        wire:click="$dispatchTo('line-items.estimate-line-item-create', 'addToEstimate', { section_id: {{$section->id}} })"
                        variant="primary"
                        icon="plus"
                        >
                        Item
                    </flux:button>
                    <flux:button disabled>
                        {{money($section->total)}}
                    </flux:button>
                </div>
            </flux:card>
        @endforeach
        <livewire:line-items.estimate-line-item-create :estimate="$estimate"/>
    </div>
</div>
