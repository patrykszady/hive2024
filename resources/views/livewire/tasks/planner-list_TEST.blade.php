<header class="flex flex-none items-center justify-between border-b border-gray-200 px-6 py-4">
    <h1 class="text-base font-semibold leading-6 text-gray-900">
        <time datetime="2022-01">January 2022</time>
    </h1>
    <div class="flex items-center">
        <div class="relative flex items-center rounded-md bg-white shadow-sm md:items-stretch">
            <button type="button"
                class="flex h-9 w-12 items-center justify-center rounded-l-md border-y border-l border-gray-300 pr-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pr-0 md:hover:bg-gray-50">
                <span class="sr-only">Previous week</span>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <button type="button"
                class="hidden border-y border-gray-300 px-3.5 text-sm font-semibold text-gray-900 hover:bg-gray-50 focus:relative md:block">Today</button>
            <span class="relative -mx-px h-5 w-px bg-gray-300 md:hidden"></span>
            <button type="button"
                class="flex h-9 w-12 items-center justify-center rounded-r-md border-y border-r border-gray-300 pl-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pl-0 md:hover:bg-gray-50">
                <span class="sr-only">Next week</span>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div class="hidden md:ml-4 md:flex md:items-center">
            <div class="relative">
                <button type="button"
                    class="flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    id="menu-button" aria-expanded="false" aria-haspopup="true">
                    Week view
                    <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!--
        Dropdown menu, show/hide based on menu state.

        Entering: "transition ease-out duration-100"
            From: "transform opacity-0 scale-95"
            To: "transform opacity-100 scale-100"
        Leaving: "transition ease-in duration-75"
            From: "transform opacity-100 scale-100"
            To: "transform opacity-0 scale-95"
        -->
                <div class="absolute right-0 z-10 mt-3 w-36 origin-top-right overflow-hidden rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                    <div class="py-1" role="none">
                        <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                            id="menu-item-0">Day view</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                            id="menu-item-1">Week view</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                            id="menu-item-2">Month view</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                            id="menu-item-3">Year view</a>
                    </div>
                </div>
            </div>
            <div class="ml-6 h-6 w-px bg-gray-300"></div>
            <button type="button"
                class="ml-6 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Add
                event</button>
        </div>
        <div class="relative ml-6 md:hidden">
            <button type="button"
                class="-mx-2 flex items-center rounded-full border border-transparent p-2 text-gray-400 hover:text-gray-500"
                id="menu-0-button" aria-expanded="false" aria-haspopup="true">
                <span class="sr-only">Open menu</span>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path
                        d="M3 10a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM8.5 10a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM15.5 8.5a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" />
                </svg>
            </button>

            <!--
        Dropdown menu, show/hide based on menu state.

        Entering: "transition ease-out duration-100"
        From: "transform opacity-0 scale-95"
        To: "transform opacity-100 scale-100"
        Leaving: "transition ease-in duration-75"
        From: "transform opacity-100 scale-100"
        To: "transform opacity-0 scale-95"
    -->
            <div class="absolute right-0 z-10 mt-3 w-36 origin-top-right divide-y divide-gray-100 overflow-hidden rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                role="menu" aria-orientation="vertical" aria-labelledby="menu-0-button" tabindex="-1">
                <div class="py-1" role="none">
                    <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                        id="menu-0-item-0">Create event</a>
                </div>
                <div class="py-1" role="none">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                        id="menu-0-item-1">Go to today</a>
                </div>
                <div class="py-1" role="none">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                        id="menu-0-item-2">Day view</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                        id="menu-0-item-3">Week view</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                        id="menu-0-item-4">Month view</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1"
                        id="menu-0-item-5">Year view</a>
                </div>
            </div>
        </div>
    </div>
</header>


{{-- GRID HORIZONAL (X?) SCROLL --}}
<div class="grid grid-flow-col auto-cols-max gap-4 overflow-x-scroll">
    <div class="w-64 h-64 bg-blue-500">Item 1</div>
    <div class="w-64 h-64 bg-green-500">Item 2</div>
    <div class="w-64 h-64 bg-red-500">Item 3</div>
    <!-- Add more items as needed -->
    <div class="w-64 h-64 bg-blue-500">Item 1</div>
    <div class="w-64 h-64 bg-green-500">Item 2</div>
    <div class="w-64 h-64 bg-red-500">Item 3</div>
    <div class="w-64 h-64 bg-blue-500">Item 1</div>
    <div class="w-64 h-64 bg-green-500">Item 2</div>
    <div class="w-64 h-64 bg-red-500">Item 3</div>
    <div class="w-64 h-64 bg-blue-500">Item 1</div>
    <div class="w-64 h-64 bg-green-500">Item 2</div>
    <div class="w-64 h-64 bg-red-500">Item 3</div>
    <div class="w-64 h-64 bg-blue-500">Item 1</div>
    <div class="w-64 h-64 bg-green-500">Item 2</div>
    <div class="w-64 h-64 bg-red-500">Item 3</div>
    <div class="w-64 h-64 bg-blue-500">Item 1</div>
    <div class="w-64 h-64 bg-green-500">Item 2</div>
    <div class="w-64 h-64 bg-red-500">Item 3</div>
    <div class="w-64 h-64 bg-blue-500">Item 1</div>
    <div class="w-64 h-64 bg-green-500">Item 2</div>
    <div class="w-64 h-64 bg-red-500">Item 3</div>
    <div class="w-64 h-64 bg-blue-500">Item 1</div>
    <div class="w-64 h-64 bg-green-500">Item 2</div>
    <div class="w-64 h-64 bg-red-500">Item 3</div>
    <div class="w-64 h-64 bg-blue-500">Item 1</div>
    <div class="w-64 h-64 bg-green-500">Item 2</div>
    <div class="w-64 h-64 bg-red-500">Item 3</div>
</div>


<x-cards class="max-w-full">
    <x-cards.body>
        <div class="isolate flex flex-auto flex-col overflow-auto bg-white">
            <div class="flex max-w-full flex-none flex-col sm:max-w-none md:max-w-full">
                {{-- sm:pr-8 --}}
                <div class="sticky top-0 z-30 flex-none bg-white shadow ring-1 ring-black ring-opacity-5">
                    {{-- MOBILE OG VIEW https://tailwindui.com/components/application-ui/data-display/calendars (Week view--}}




                                        <!-- Events -->
                        {{-- <ol class="col-start-1 col-end-2 row-start-1 grid grid-cols-1 sm:grid-cols-7 sm:pr-8"
                            style="grid-template-rows: 1.75rem repeat(288, minmax(0, 1fr)) auto">
                            <li class="relative mt-px flex sm:col-start-3" style="grid-row: 74 / span 12">
                                <a href="#"
                                    class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-blue-50 p-2 text-xs leading-5 hover:bg-blue-100">
                                    <p class="order-1 font-semibold text-blue-700">Breakfast</p>
                                    <p class="text-blue-500 group-hover:text-blue-700"><time
                                            datetime="2022-01-12T06:00">6:00 AM</time></p>
                                </a>
                            </li>
                            <li class="relative mt-px flex sm:col-start-3" style="grid-row: 92 / span 30">
                                <a href="#"
                                    class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-pink-50 p-2 text-xs leading-5 hover:bg-pink-100">
                                    <p class="order-1 font-semibold text-pink-700">Flight to Paris</p>
                                    <p class="text-pink-500 group-hover:text-pink-700"><time
                                            datetime="2022-01-12T07:30">7:30 AM</time></p>
                                </a>
                            </li>
                            <li class="relative mt-px hidden sm:col-start-6 sm:flex" style="grid-row: 122 / span 24">
                                <a href="#"
                                    class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-gray-100 p-2 text-xs leading-5 hover:bg-gray-200">
                                    <p class="order-1 font-semibold text-gray-700">Meeting with design team at Disney</p>
                                    <p class="text-gray-500 group-hover:text-gray-700"><time
                                            datetime="2022-01-15T10:00">10:00 AM</time></p>
                                </a>
                            </li>
                        </ol> --}}


                        <div>
                            {{-- <x-page.top
                                class="lg:max-w-5xl"
                                h1="Project Schedule"
                                p=""
                                >

                                //incoorperate HEADER FROM planner-list_TEST
                            </x-page.top> --}}
                            <x-cards class="max-w-full">
                                <x-cards.heading>
                                    <x-slot name="left">
                                        <h1>Schedule</h1>
                                        {{-- <p>Expense and related details like Expense Splits and Expense Receipts.</p> --}}
                                    </x-slot>

                                    {{-- <x-slot name="right">
                                        <x-cards.button
                                            :button_color="'white'"
                                            wire:click="$dispatchTo('expenses.expenses-associated', 'addAssociatedExpense', { expense: {{$expense->id}}})"
                                            >
                                            Associated
                                        </x-cards.button>
                                    </x-slot> --}}
                                </x-cards.heading>
                                <x-cards.body>
                                    <div class="isolate flex flex-auto flex-col overflow-auto bg-white">
                                        <div class="flex max-w-full flex-col sticky top-5">
                                            {{-- MOBILE OG VIEW https://tailwindui.com/components/application-ui/data-display/calendars (Week view--}}

                                            {{--  sm:hidden --}}
                                            {{-- <div class="grid grid-cols-7 text-sm leading-6 text-gray-500">
                                                <button type="button" class="flex flex-col items-center pb-3 pt-2">M <span
                                                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">10</span></button>
                                                <button type="button" class="flex flex-col items-center pb-3 pt-2">T <span
                                                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">11</span></button>
                                                <button type="button" class="flex flex-col items-center pb-3 pt-2">W <span
                                                        class="mt-1 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">12</span></button>
                                                <button type="button" class="flex flex-col items-center pb-3 pt-2">T <span
                                                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">13</span></button>
                                                <button type="button" class="flex flex-col items-center pb-3 pt-2">F <span
                                                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">14</span></button>
                                                <button type="button" class="flex flex-col items-center pb-3 pt-2">S <span
                                                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">15</span></button>
                                                <button type="button" class="flex flex-col items-center pb-3 pt-2">S <span
                                                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">16</span></button>
                                            </div> --}}

                                            {{-- PROJECTS FOREACH HERE --}}
                                            <div
                                                {{-- sm:grid hidden--}}
                                                {{--  gap-4 --}}
                                                class="grid divide-x divide-gray-100 grid-flow-col auto-cols-max overflow-x-scroll"
                                                >
                                                {{-- First. leftmost table column on the first row.  --}}
                                                <div class="col-end-1 w-14"></div>

                                                @foreach($projects as $project)
                                                    <div class="flex items-center justify-center p-3 font-semibold border-b-4">
                                                        {{-- <span>{{$project->address}}</span> --}}
                                                        <span>
                                                            {{$project->address}}
                                                            <br>
                                                            <span class="items-center justify-center font-normal italic text-gray-600">
                                                                {{$project->project_name}}
                                                            </span>
                                                        </span>
                                                    </div>
                                                    {{-- Selected Project (why ... makes sense for the tailwind calendar template we're using .. might want to implenent this on the hirizontal Days /dates div) --}}
                                                    {{-- <div class="flex items-center justify-center py-3">
                                                        <span class="flex items-baseline">Wed <span
                                                                class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">12</span></span>
                                                    </div> --}}
                                                @endforeach
                                            </div>

                                            {{-- HORIZONTAL LINES HERE --}}
                                            <div class="flex flex-auto">
                                                <div class="sticky left-0 z-10 w-14 flex-none bg-white ring-1 ring-gray-100"></div>
                                                <div class="grid flex-auto grid-cols-1 grid-rows-1">
                                                    <!-- Horizontal lines -->
                                                    <div
                                                        class="col-start-1 col-end-2 row-start-1 grid divide-y divide-gray-100"
                                                        style="grid-template-rows: repeat(14, minmax(3.5rem, 1fr))"
                                                        >
                                                        {{-- First/dead div  --}}
                                                        <div class="row-end-1 h-7"></div>
                                                        @foreach($days as $day)
                                                            <div>
                                                                <div
                                                                    class="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-800"
                                                                    >
                                                                    <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                                                                    <br>
                                                                    <span class="italics">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                                                                </div>
                                                            </div>
                                                            {{-- Next task for this day --}}
                                                            {{-- "New Task for this day button on the last one? on hover?" --}}
                                                            <div></div>
                                                        @endforeach
                                                    </div>

                                                    <!-- Vertical lines -->
                                                    <div
                                                        {{--  sm:grid sm:grid-cols-7 --}}
                                                        class="col-start-1 col-end-2 row-start-1 grid-cols-{{$projects->count()}} grid-rows-1 divide-x divide-gray-100 grid"
                                                        >
                                                        @for($x = 1; $x <= $projects->count(); $x++)
                                                            <div class="col-start-{{$x}} row-span-full"></div>
                                                            {{-- <div class="col-start-8 row-span-full w-8"></div> --}}
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </x-cards.body>
                            </x-cards>
                        </div>




<x-cards class="max-w-full">
    <x-cards.heading>
        <x-slot name="left">
            <h1>Schedule</h1>
            {{-- <p>Expense and related details like Expense Splits and Expense Receipts.</p> --}}
        </x-slot>

        {{-- <x-slot name="right">
            <x-cards.button
                :button_color="'white'"
                wire:click="$dispatchTo('expenses.expenses-associated', 'addAssociatedExpense', { expense: {{$expense->id}}})"
                >
                Associated
            </x-cards.button>
        </x-slot> --}}
    </x-cards.heading>
    <x-cards.body>


    </x-cards.body>
</x-cards>



{{-- HORIZONTAL LINES HERE --}}
<div class="flex flex-auto overflow-x-scroll">
    <div class="sticky left-0 z-10 w-14 flex-none bg-white ring-1 ring-gray-100"></div>
    <div class="grid flex-auto grid-cols-1 grid-rows-1">
        <!-- Horizontal lines -->
        <div class="col-start-1 col-end-2 row-start-1 grid divide-y divide-gray-100"
            style="grid-template-rows: repeat(14, minmax(3.5rem, 1fr))">

            {{-- First/dead div  --}}
            <div class="row-end-1 h-7"></div>
            @foreach($days as $day)
                <div>
                    <div
                        class="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-800"
                        >
                        <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                        <br>
                        <span class="italics">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                    </div>
                </div>
                {{-- Next task for this day --}}
                {{-- "New Task for this day button on the last one? on hover?" --}}
                <div></div>
            @endforeach
        </div>

        <!-- Vertical lines -->
        <div class="col-start-1 col-end-2 row-start-1 grid-cols-{{$projects->count()}} grid-rows-1 divide-x divide-gray-100 grid">
            @for($x = 1; $x <= $projects->count(); $x++)
                <div class="col-start-{{$x}} row-span-full"></div>
                {{-- <div class="col-start-8 row-span-full w-8"></div> --}}
            @endfor
        </div>

        <!-- Events -->
        {{-- <ol class="col-start-1 col-end-2 row-start-1 grid grid-cols-1 sm:grid-cols-7 sm:pr-8"
            style="grid-template-rows: 1.75rem repeat(288, minmax(0, 1fr)) auto">
            <li class="relative mt-px flex sm:col-start-3" style="grid-row: 74 / span 12">
                <a href="#"
                    class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-blue-50 p-2 text-xs leading-5 hover:bg-blue-100">
                    <p class="order-1 font-semibold text-blue-700">Breakfast</p>
                    <p class="text-blue-500 group-hover:text-blue-700"><time
                            datetime="2022-01-12T06:00">6:00 AM</time></p>
                </a>
            </li>
            <li class="relative mt-px flex sm:col-start-3" style="grid-row: 92 / span 30">
                <a href="#"
                    class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-pink-50 p-2 text-xs leading-5 hover:bg-pink-100">
                    <p class="order-1 font-semibold text-pink-700">Flight to Paris</p>
                    <p class="text-pink-500 group-hover:text-pink-700"><time
                            datetime="2022-01-12T07:30">7:30 AM</time></p>
                </a>
            </li>
            <li class="relative mt-px hidden sm:col-start-6 sm:flex" style="grid-row: 122 / span 24">
                <a href="#"
                    class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-gray-100 p-2 text-xs leading-5 hover:bg-gray-200">
                    <p class="order-1 font-semibold text-gray-700">Meeting with design team at Disney</p>
                    <p class="text-gray-500 group-hover:text-gray-700"><time
                            datetime="2022-01-15T10:00">10:00 AM</time></p>
                </a>
            </li>
        </ol> --}}
    </div>
</div>



{{-- WORKING --}}
<div class="isolate flex flex-auto flex-col bg-white">
    {{-- sm:max-w-none md:max-w-full --}}
    <div style="width: 165%" class="flex max-w-full flex-none flex-col">
        {{-- PROJECTS FOREACH HERE --}}
        {{--  sm:pr-8 --}}
        <div class="sticky top-0 z-30 flex-none bg-white shadow ring-1 ring-black ring-opacity-5">
            {{-- <div class="grid grid-cols-7 text-sm leading-6 text-gray-500 sm:hidden">
                <button type="button" class="flex flex-col items-center pb-3 pt-2">M <span
                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">10</span></button>
                <button type="button" class="flex flex-col items-center pb-3 pt-2">T <span
                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">11</span></button>
                <button type="button" class="flex flex-col items-center pb-3 pt-2">W <span
                        class="mt-1 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">12</span></button>
                <button type="button" class="flex flex-col items-center pb-3 pt-2">T <span
                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">13</span></button>
                <button type="button" class="flex flex-col items-center pb-3 pt-2">F <span
                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">14</span></button>
                <button type="button" class="flex flex-col items-center pb-3 pt-2">S <span
                        class="mt-1 flex h-8 w-8 items-center justify-center font-semibold text-gray-900">15</span></button>
                <button type="button" class="flex flex-col items-center pb-3 pt-2">S <span
                        class="mt-1 flex h-8 w-8 items-cen ter justify-center font-semibold text-gray-900">16</span></button>
            </div> --}}

            {{-- PROJECTS FOREACH HERE --}}
            <div class="divide-x divide-gray-100 border-r border-gray-100 text-sm leading-6 text-gray-500 grid grid-flow-col auto-cols-max overflow-x-scroll">
                {{-- First. leftmost table column on the first row.  --}}
                <div class="col-end-1 w-14"></div>

                @foreach($projects as $project)
                    <div class="flex items-center justify-center p-3 font-semibold border-b-4">
                        {{-- <span>{{$project->address}}</span> --}}
                        <span>
                            {{$project->address}}
                            <br>
                            <span class="items-center justify-center font-normal italic text-gray-600">
                                {{$project->project_name}}
                            </span>
                        </span>
                    </div>
                    {{-- Selected Project (why ... makes sense for the tailwind calendar template we're using .. might want to implenent this on the hirizontal Days /dates div) --}}
                    {{-- <div class="flex items-center justify-center py-3">
                        <span class="flex items-baseline">Wed <span
                                class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">12</span></span>
                    </div> --}}
                @endforeach
            </div>
        </div>

        {{-- HORIZONTAL LINES HERE --}}
        <div class="flex flex-auto overflow-x-scroll">
            <div class="sticky left-0 z-10 w-14 flex-none bg-white ring-1 ring-gray-100"></div>
            <div class="grid flex-auto grid-cols-1 grid-rows-1">
                <!-- Horizontal lines -->
                <div class="col-start-1 col-end-2 row-start-1 grid divide-y divide-gray-100"
                    style="grid-template-rows: repeat(14, minmax(3.5rem, 1fr))">

                    {{-- First/dead div  --}}
                    <div class="row-end-1 h-7"></div>
                    @foreach($days as $day)
                        <div>
                            <div
                                class="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-800"
                                >
                                <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                                <br>
                                <span class="italics">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                            </div>
                        </div>
                        {{-- Next task for this day --}}
                        {{-- "New Task for this day button on the last one? on hover?" --}}
                        <div></div>
                    @endforeach
                </div>

                <!-- Vertical lines -->
                <div class="col-start-1 col-end-2 row-start-1 grid-cols-{{$projects->count()}} grid-rows-1 divide-x divide-gray-100 grid">
                    @for($x = 1; $x <= $projects->count(); $x++)
                        <div class="col-start-{{$x}} row-span-full"></div>
                        {{-- <div class="col-start-8 row-span-full w-8"></div> --}}
                    @endfor
                </div>

                <!-- Events -->
                {{-- <ol class="col-start-1 col-end-2 row-start-1 grid grid-cols-1 sm:grid-cols-7 sm:pr-8"
                    style="grid-template-rows: 1.75rem repeat(288, minmax(0, 1fr)) auto">
                    <li class="relative mt-px flex sm:col-start-3" style="grid-row: 74 / span 12">
                        <a href="#"
                            class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-blue-50 p-2 text-xs leading-5 hover:bg-blue-100">
                            <p class="order-1 font-semibold text-blue-700">Breakfast</p>
                            <p class="text-blue-500 group-hover:text-blue-700"><time
                                    datetime="2022-01-12T06:00">6:00 AM</time></p>
                        </a>
                    </li>
                    <li class="relative mt-px flex sm:col-start-3" style="grid-row: 92 / span 30">
                        <a href="#"
                            class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-pink-50 p-2 text-xs leading-5 hover:bg-pink-100">
                            <p class="order-1 font-semibold text-pink-700">Flight to Paris</p>
                            <p class="text-pink-500 group-hover:text-pink-700"><time
                                    datetime="2022-01-12T07:30">7:30 AM</time></p>
                        </a>
                    </li>
                    <li class="relative mt-px hidden sm:col-start-6 sm:flex" style="grid-row: 122 / span 24">
                        <a href="#"
                            class="group absolute inset-1 flex flex-col overflow-y-auto rounded-lg bg-gray-100 p-2 text-xs leading-5 hover:bg-gray-200">
                            <p class="order-1 font-semibold text-gray-700">Meeting with design team at Disney</p>
                            <p class="text-gray-500 group-hover:text-gray-700"><time
                                    datetime="2022-01-15T10:00">10:00 AM</time></p>
                        </a>
                    </li>
                </ol> --}}
            </div>
        </div>
    </div>
</div>



{{-- GRID INTRO --}}
<div class="flex max-w-full flex-none flex-col">
    <div class="grid grid-flow-col grid-cols-{{$projects->count()}} divide-x divide-solid divide-gray-300 auto-cols-max overflow-x-scroll sticky top-0 z-30">
        {{-- First. leftmost table column on the first row.  --}}
        <div class="col-end-1 w-14"></div>
        @foreach($projects as $project_index => $project)
            {{-- class="sticky top-0 z-30" --}}
            <div
                {{-- wire:click="$dispatchTo('tasks.task-create', 'addTask', { project_id: {{$project->id}}, date: '{{ $day['database_date'] }}' })"
                class="pl-1 cursor-pointer hover:bg-gray-100 {{$day['is_today'] == TRUE ? 'text-indigo-600' : ''}}" --}}
                >
                <div class="w-64 items-center justify-center p-3 font-semibold border-b-4">
                    {{-- <span>{{$project->address}}</span> --}}
                    <span>
                        {{ Str::limit($project->address, 18) }}
                        <br>
                        <span class="items-center justify-center font-normal italic text-gray-600">
                            {{ Str::limit($project->project_name, 18) }}
                        </span>
                    </span>
                </div>
                {{-- GRID HERE --}}
                <div class="divide-y divide-gray-400">
                    @foreach($days as $day)
                    <x-cards>
                        <div class="grid-stack mb-4"
                            x-data="{
                                init() {
                                    let grids = GridStack.initAll({
                                        column: 1,
                                        cellHeight: '60px',
                                        {{-- cellWidth: '100px', --}}
                                        float: false,
                                        {{-- resizable: {
                                            handles: 'n, s'
                                        }, --}}
                                        minRow: 2,
                                        margin: 1,
                                        acceptWidgets: true,
                                        {{-- removable: '.trash', // drag-out delete class --}}
                                    });

                                    grids[{{$project_index}}].on('added change', function(event, items) {
                                        {{-- let newItems = [];
                                        items.forEach ((el) => {
                                            newItems.push({_id: el._id, x: el.x, y: el.y, w: el.w, task_id: el.id});
                                        });

                                        $wire.taskMoved(newItems); --}}
                                    });
                                    {{-- GridStack.setupDragIn('.noDateTasks .grid-stack-item', { appendTo: 'body' }); --}}
                                }
                            }"
                            >

                            <div class="grid-stack-item cursor-pointer" gs-no-resize="true">
                                <div class="m-2">
                                    <div class="pl-1 grid-stack-item-content border border-solid border-indigo-400 bg-indigo-50 h-14 hover:bg-white font-bold rounded-md text-clip overflow-hidden">
                                        <span
                                            class="text-indigo-800"
                                            >
                                            Item 1 {{$project->id}}
                                        </span>
                                        <br>
                                        <span class="text-sm font-medium text-gray-600">{{$day['formatted_date']}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-cards>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-all.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-extra.min.css">
</div>














{{-- HORIZONTAL LINES HERE --}}
<div class="flex flex-auto">
    <div class="sticky left-0 z-10 w-14 flex-none bg-white ring-1 ring-gray-100"></div>
    <div class="grid flex-auto grid-cols-1 grid-rows-1">
        <!-- Horizontal lines -->

            {{-- First/dead div  --}}
            <div class="row-end-1 h-7"></div>
            @foreach($days as $day)
                <div>
                    <div
                        class="sticky left-0 z-20 -ml-14 -mt-2.5 w-14 pr-2 text-right text-xs leading-5 text-gray-800"
                        >
                        <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                        <br>
                        <span class="italics">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                    </div>
                </div>
                {{-- Next task for this day --}}
                {{-- "New Task for this day button on the last one? on hover?" --}}
                <div></div>
            @endforeach

    </div>
</div>




























{{-- WORKING --}}
{{--  class="isolate flex flex-auto flex-col" x-data="{}" --}}

{{-- class="flex flex-none flex-col" --}}
<div>
    {{-- PROJECTS FOREACH HERE --}}
    <div class="sticky top-0 z-10 flex-none shadow bg-white overflow-x-scroll" x-bind="scrollSync">
        {{-- PROJECTS FOREACH HERE --}}
        <div class="divide-x divide-gray-100 text-sm leading-6 text-gray-500 grid grid-flow-col auto-cols-max">
            {{-- First. leftmost table column on the first row.  --}}
            <div class="col-end-1 w-14 bg-white"></div>

            @foreach($projects as $project)
            {{-- items-center justify-center  --}}
                <div class="w-48 p-3 border-b-4">
                    <span class="font-semibold text-gray-800">
                        {{ Str::limit($project->address, 22) }}
                    </span>
                    <br>
                    <span class="font-normal italic text-gray-600">
                        {{ Str::limit($project->project_name, 22) }}
                    </span>
                </div>
                {{-- Selected Project (why ... makes sense for the tailwind calendar template we're using .. might want to implenent this on the hirizontal Days /dates div) --}}
                {{-- <div class="flex items-center justify-center py-3">
                    <span class="flex items-baseline">Wed <span
                            class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">12</span></span>
                </div> --}}
            @endforeach
        </div>
    </div>

    <div class="overflow-x-scroll bg-white pt-8" x-bind="scrollSync">
        @foreach($days as $day)
            {{-- PROJECTS FOREACH HERE --}}
            <div class="divide-x divide-gray-100 text-sm text-gray-500 grid grid-flow-col">
                {{-- First. leftmost table column on the first row.  --}}
                <div class="col-end-1 w-14 bg-white"></div>

                <div
                    class="sticky left-0 z-20 -ml-14 mt-4 w-14 pr-2 text-right text-xs text-gray-800 bg-white"
                    >
                    <span class="font-semibold text-gray-700">{{strtok($day['formatted_date'], ',')}}</span>
                    <br>
                    <span class="italics">{{substr($day['formatted_date'], strpos($day['formatted_date'], ', ') + 2)}}</span>
                </div>
                @foreach($projects as $project)
                    <div class="w-48 p-3 border-b-4">
                        <span class="font-semibold text-gray-800">
                            {{ Str::limit($project->address, 22) }}
                        </span>
                        <br>
                        <span class="font-normal italic text-gray-600">
                            {{ Str::limit($project->project_name, 22) }}
                        </span>
                    </div>
                    {{-- Selected Project (why ... makes sense for the tailwind calendar template we're using .. might want to implenent this on the hirizontal Days /dates div) --}}
                    {{-- <div class="flex items-center justify-center py-3">
                        <span class="flex items-baseline">Wed <span
                                class="ml-1.5 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">12</span></span>
                    </div> --}}
                @endforeach
            </div>
        @endforeach
    </div>
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.store('scrollSync', {
                scrollLeft: 0,
            })
            Alpine.bind('scrollSync', {
                '@scroll'(){
                    this.$store.scrollSync.scrollLeft = this.$el.scrollLeft
                },
                'x-effect'() {
                    this.$el.scrollLeft = this.$store.scrollSync.scrollLeft
                }
            })
        })
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-all.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/10.1.2/gridstack-extra.min.css">
</div>




