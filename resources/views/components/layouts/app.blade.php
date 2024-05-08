<!DOCTYPE html>
<html class="h-full bg-gray-50" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('components.layouts.head')
    {{-- class="font-sans antialiased" --}}
    <body class="h-full">
        <div x-cloak x-data="{ sidebarOpen: false }" @keydown.window.escape="sidebarOpen = false">
            <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
            <div x-show="sidebarOpen" class="relative z-30 lg:hidden" role="dialog" aria-modal="true">
                <!--
                Off-canvas menu backdrop, show/hide based on off-canvas menu state.

                Entering: "transition-opacity ease-linear duration-300"
                From: "opacity-0"
                To: "opacity-100"
                Leaving: "transition-opacity ease-linear duration-300"
                From: "opacity-100"
                To: "opacity-0"
                    -->
                <div class="fixed inset-0 bg-gray-600 opacity-80"
                    x-description="Off-canvas menu backdrop, show/hide based on off-canvas menu state."
                    x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                </div>

                <div class="fixed inset-0 flex noprint">
                    <!--
                    Off-canvas menu, show/hide based on off-canvas menu state.

                    Entering: "transition ease-in-out duration-300 transform"
                        From: "-translate-x-full"
                        To: "translate-x-0"
                    Leaving: "transition ease-in-out duration-300 transform"
                        From: "translate-x-0"
                        To: "-translate-x-full"
                    -->
                    <div class="relative flex flex-1 w-full max-w-xs mr-16" x-show="sidebarOpen"
                        x-description="Off-canvas menu, show/hide based on off-canvas menu state."
                        x-transition:enter="transition ease-in-out duration-300 transform"
                        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in-out duration-300 transform"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                        <!--
                        Close button, show/hide based on off-canvas menu state.

                        Entering: "ease-in-out duration-300"
                        From: "opacity-0"
                        To: "opacity-100"
                        Leaving: "ease-in-out duration-300"
                        From: "opacity-100"
                        To: "opacity-0"
                        -->
                        <div class="absolute top-0 flex justify-center w-16 pt-5 left-full"
                            x-description="Close button, show/hide based on off-canvas menu state."
                            x-transition:enter="transition-opacity ease-linear duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity ease-linear duration-300"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                            <button type="button" class="-m-2.5 p-2.5" x-on:click="sidebarOpen = false">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Sidebar component, swap this element with another sidebar if you like -->
                        <div class="flex flex-col px-6 pb-2 overflow-y-auto bg-white grow gap-y-5 w-72"
                            @click.away="sidebarOpen = false">
                            @include('components.layouts.nav.navigation-menu')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <div class="hidden lg:fixed lg:inset-y-0 lg:z-10 lg:flex lg:w-72 lg:flex-col noprint">
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <div class="flex flex-col px-6 overflow-y-auto bg-white border-r border-gray-200 lg:h-screen grow gap-y-5">
                    @include('components.layouts.nav.navigation-menu')
                </div>
            </div>

            {{-- if modal opened hide... --}}
            {{-- tob bar on mobile --}}
            <div class="sticky top-0 z-20 flex items-center px-4 py-4 bg-white shadow-sm gap-x-6 sm:px-6 lg:hidden noprint">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" x-on:click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex-1 text-sm font-semibold leading-6 text-gray-900">Dashboard</div>
                <a href="{{ route('dashboard') }}">
                    <span class="sr-only">Your dashboard</span>
                    <img class="w-8 h-8 rounded-full" src="{{ asset('favicon.png') }}" alt="{{ env('APP_NAME') }}">
                </a>
            </div>

            <main class="py-10 lg:pl-72">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Your content -->
                    {{ $slot }}


                    {{-- @if(session()->has('notify'))
                        @php
                        $flash = session()->get('notify');
                        $this->dispatch('notify',
                            type: $flash[0],
                            content: $flash[1],
                            route: isset($flash[2]) ? $flash[2] : NULL
                        );
                        @endphp
                    @endif --}}
                    {{-- @if(session()->has('notify'))
                        <script>
                            window.onload = function() {
                                window.dispatchEvent(new CustomEvent('notify', {
                                    type: '{{ session('notify') }}'
                                }));
                            }
                        </script>
                    @endif --}}
                    @include('components.notify')
                </div>
            </main>
        </div>

        @livewireScripts
        <script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.4.0/dist/livewire-sortable.js"></script>
    </body>
</html>
