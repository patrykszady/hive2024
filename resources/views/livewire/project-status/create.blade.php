{{-- PROJECT LIFESPAN / STATUS --}}
<x-cards.wrapper>
    <x-cards.heading>
        <x-slot name="left">
            <h1>Project Lifespan</b></h1>
        </x-slot>
    </x-cards.heading>
    <x-cards.body>
        <ul role="list" class="p-6 space-y-6">
            {{-- 2nd to last gets CHECKMARK (Could be first (estimate) ) --}}
            @foreach($statuses as $status)
                <li class="relative flex gap-x-4">
                    <div class="absolute top-0 left-0 flex justify-center w-6 -bottom-6">
                        <div class="w-px bg-gray-200"></div>
                    </div>
                    <div class="relative flex items-center justify-center flex-none w-6 h-6 bg-white">
                        @if($loop->last)
                            <svg class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
                        @endif
                    </div>
                    <p class="flex-auto py-0.5 text-sm leading-5 text-gray-500">
                        <span class="font-medium text-gray-900">
                            {{$status->title}}
                        </span>
                        {{$status->start_date->format('m/d/y')}}
                    </p>
                    <time datetime="{{$status->start_date}}" class="flex-none py-0.5 text-xs leading-5 text-gray-500">{{$status->start_date->diffForHumans(now(), Carbon\CarbonInterface::DIFF_ABSOLUTE)}}</time>
                </li>
            @endforeach

            {{-- <li class="relative flex gap-x-4">
                <div class="absolute top-0 left-0 flex justify-center w-6 -bottom-5">
                    <div class="w-px bg-gray-200"></div>
                </div>
                <div class="relative flex items-center justify-center flex-none w-6 h-6 bg-white">
                    <div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
                </div>
                <p class="flex-auto py-0.5 text-xs leading-5 text-gray-500"><span class="font-medium text-gray-900">Alex
                        Curren</span> viewed the invoice.</p>
                <time datetime="2023-01-24T09:12" class="flex-none py-0.5 text-xs leading-5 text-gray-500">2d ago</time>
            </li>

            <li class="relative flex gap-x-4">
                <div class="absolute top-0 left-0 flex justify-center w-6 h-6">
                    <div class="w-px bg-gray-200"></div>
                </div>
                <div class="relative flex items-center justify-center flex-none w-6 h-6 bg-white">
                    <svg class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="flex-auto py-0.5 text-xs leading-5 text-gray-500"><span class="font-medium text-gray-900">
                    Alex Curren</span> paid the invoice.</p>
                <time datetime="2023-01-24T09:20" class="flex-none py-0.5 text-xs leading-5 text-gray-500">1d ago</time>
            </li> --}}

            <li class="relative flex gap-x-4">
                <div class="absolute left-0 flex justify-center w-6 bottom-5 -top-5">
                    <div class="w-px bg-gray-200"></div>
                </div>
                <div class="relative flex items-center justify-center flex-none w-6 h-6 bg-white">
                    <div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
                </div>

                {{-- <p class="flex-auto py-0.5 text-sm leading-5 text-gray-500">
                    <span class="font-medium text-gray-900">Update Project Status</span>
                </p> --}}

                <div class="flex max-w-lg -mt-1 rounded-md shadow-sm">
                    <input
                        name="project_status_date"
                        wire:model.live="project_status_date"
                        name="project_status_date"
                        id="project_status_date"
                        type="date"
                        class="block w-full min-w-0 text-sm placeholder-gray-200 border-gray-300 rounded-none hover:bg-gray-50 rounded-l-md focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </input>
                    <select
                        name="project_status"
                        wire:model.live="project_status"
                        name="project_status"
                        id="new_project_id"
                        class="block w-full min-w-0 text-sm placeholder-gray-200 border-gray-300 rounded-none border-l-none hover:bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        @include('livewire.projects._status_options')
                    </select>

                    <button
                        type="button"
                        wire:click="update_project"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50"
                        class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 focus:ring-offset-0 focus:ring-2 ring-1 ring-inset ring-gray-300 hover:bg-gray-100 focus:ring-indigo-500"
                        >
                        Change
                    </button>
                </div>
            </li>
            <x-forms.error errorName="project_status"/>
        </ul>
    </x-cards.body>
</x-cards.wrapper>
