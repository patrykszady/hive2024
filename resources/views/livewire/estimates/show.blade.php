<div class="grid max-w-3xl grid-cols-4 gap-4 mx-auto lg:max-w-5xl sm:px-6">
    {{--  open_disclosure="FALSE" --}}
    <div class="col-span-4 space-y-4 lg:col-span-4">
        {{-- SECTIONS --}}
        @foreach($sections as $index => $section)
            <x-cards>
                <x-cards.heading :accordian="TRUE">
                    <x-slot name="left">
                        <label for="section" class="sr-only">Section Name</label>
                        <input
                            id="sections.{{$index}}.name"
                            name="sections.{{$index}}.name"
                            type="text"
                            autocomplete="section"
                            required
                            class="flex-auto text-gray-900 border-0 rounded-md shadow-sm ring-1 ring-gray-300 focus:ring-2 ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            placeholder="Section Name"
                        >
                    </x-slot>

                    {{-- IF section is opened/not collapsed --}}
                    <x-slot name="right">
                        <x-cards.button
                            type="button"
                            wire:click="$dispatchTo('line-items.estimate-line-item-create', 'addToEstimate', { section_id: {{$section->id}}, section_item_count: {{$estimate->estimate_line_items()->where('section_id', $section->id)->count()}} })"
                            >
                            Add Item
                        </x-cards.button>
                    </x-slot>
                </x-cards.heading>
                <x-cards.body>
                    {{--  divide-y divide-gray-300 --}}
                    <table class="min-w-full break-inside-auto">
                        <thead class="text-gray-900 border-b border-gray-400">
                            <tr>
                                {{-- first th --}}
                                <th
                                    scope="col"
                                    class="hidden px-3 py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell"
                                    >
                                </th>
                                <th
                                    scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-3/4 sm:w-1/2"
                                    >
                                    Item
                                </th>
                                <th
                                    scope="col"
                                    class="hidden px-3 py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell"
                                    >
                                    Quantity
                                </th>
                                <th
                                    scope="col"
                                    class="hidden px-3 py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell"
                                    >
                                    Unit
                                </th>
                                <th scope="col"
                                    class="hidden px-3 py-3.5 text-right text-sm font-semibold text-gray-900 sm:table-cell"
                                    >
                                    Cost
                                </th>
                                {{-- last th --}}
                                <th
                                    scope="col"
                                    class="py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-6"
                                    >
                                    Total
                                </th>
                            </tr>
                        </thead>

                        <tbody wire:sortable="itemsRearrangeOrder">
                            {{-- line_items where section is this section_id... --}}
                            @foreach($estimate->estimate_line_items()->where('section_id', $section->id)->orderBy('section_index', 'ASC')->get() as $key => $estimate_line_item)
                                <tr
                                    class="border-b border-gray-400 hover:bg-gray-100 break-inside-auto break-after-auto"
                                    @if($section->items_rearrange == TRUE) wire:sortable.item="{{ $estimate_line_item->id }}" @endif
                                    wire:key="item-{{ $estimate_line_item->id }}"
                                    wire:target="itemsRearrangeOrder"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50"
                                    >
                                    <td class="hidden px-3 py-5 text-right text-gray-500 align-text-top text-md sm:table-cell bg-gray-50">{{$index + 1}}.{{$estimate_line_item->section_index}}</td>

                                    <td class="pl-4 pr-3 text-md max-w-0 sm:pl-6 bg-gray-50">
                                        <a
                                            class="cursor-pointer"
                                            wire:click="$dispatchTo('line-items.estimate-line-item-create', 'editOnEstimate', { estimate_line_item_id: {{$estimate_line_item}}, section_id: {{$section->id}} })"
                                            >
                                            <div class="text-lg font-medium text-gray-900">{{$estimate_line_item->name}}</div>
                                            <div class="text-xs font-bold text-indigo-900">{{$estimate_line_item->category}}/{{$estimate_line_item->sub_category}}</div>
                                        </a>
                                    </td>

                                    <td class="hidden px-3 py-5 text-right text-gray-500 align-text-top text-md sm:table-cell bg-gray-50">{{$estimate_line_item->unit_type !== 'no_unit' ? $estimate_line_item->quantity : ''}}</td>
                                    <td class="hidden px-3 py-5 text-right text-gray-500 align-text-top text-md sm:table-cell bg-gray-50">{{$estimate_line_item->unit_type !== 'no_unit' ? $estimate_line_item->unit_type : ''}}</td>
                                    <td class="hidden px-3 py-5 text-right text-gray-500 align-text-top text-md sm:table-cell bg-gray-50">{{$estimate_line_item->unit_type !== 'no_unit' ? money($estimate_line_item->cost) : ''}}</td>
                                    {{-- last td --}}
                                    <td class="py-5 pl-3 pr-4 text-right text-gray-800 align-text-top text-md sm:pr-6 bg-gray-50">{{money($estimate_line_item->total)}}</td>
                                </tr>

                                {{-- remove if sorting --}}
                                @if($section->items_rearrange == FALSE)
                                    <tr
                                        {{-- : @entangle($section->items_rearrange) --}}
                                        {{-- x-data="{ sections.{{$index}}.items_rearrange }"
                                        x-show="sections.{{$index}}.items_rearrange"
                                        x-transition --}}
                                        class="border-b border-gray-400"
                                        >
                                        {{-- first td --}}
                                        <td class="hidden sm:table-cell"></td>
                                        <td class="pb-1 pl-4 pr-3 text-md max-w-0 sm:pl-6" colspan="5">
                                            <div class="flex flex-col hidden mt-1 sm:block">
                                                <span class="text-gray-700">{{$estimate_line_item->desc}}</span>
                                                @if($estimate_line_item->notes)
                                                    <hr>
                                                    <span class="text-gray-500"><i>{{$estimate_line_item->notes}}</i></span>
                                                @endif
                                                {{-- <span class="hidden sm:inline">·</span>
                                                <span>$100</span> --}}
                                            </div>
                                            {{-- MOBILE VIEW DIV --}}
                                            <div class="flex flex-col mt-1 text-gray-500 sm:block sm:hidden">
                                                <span>{{$estimate_line_item->unit_type !== 'no_unit' ? $estimate_line_item->quantity . ' ' . $estimate_line_item->unit_type . ' @ ' .money($estimate_line_item->cost) . '/each' : ''}}</span>
                                                {{-- <span class="hidden sm:inline">·</span>
                                                <span>$100</span> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </x-cards.body>
                <x-cards.footer>
                    <button></button>
                    <h3>Section Total: {{money($section->total)}}</h3>
                </x-cards.footer>
            </x-cards>
        @endforeach
    </div>

    {{-- <livewire:line-items.estimate-line-item-create :estimate="$estimate"/> --}}
</div>

