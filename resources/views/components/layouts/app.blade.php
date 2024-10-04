<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    {{-- HEAD --}}
    @include('components.layouts.head')

    {{-- BODY --}}
    <body class="min-h-screen bg-gray-100 dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <flux:brand href="{{route('dashboard')}}" logo="{{ asset('favicon.png') }}" name="{{ env('APP_NAME') }}" />

            <flux:input as="button" variant="filled" placeholder="Search..." icon="magnifying-glass" />

            <flux:navlist variant="outline">
                <flux:navlist.item wire:navigate.hover icon="home" href="/dashboard">Home</flux:navlist.item>
                <flux:navlist.item wire:navigate.hover icon="folder" href="/projects">Projects</flux:navlist.item>
                <flux:navlist.item wire:navigate.hover icon="calendar" href="/planner_schedule">Planner</flux:navlist.item>

                @canany(['viewAny', 'create'], App\Models\Expense::class)
                    <flux:navlist.group expandable heading="Expenses" class="hidden lg:grid">
                        <flux:navlist.item wire:navigate.hover href="/expenses" icon="banknotes">Expenses</flux:navlist.item>
                        <flux:navlist.item wire:navigate.hover href="/checks" icon="pencil-square">Checks</flux:navlist.item>
                    </flux:navlist.group>
                @endcanany

                <flux:navlist.item wire:navigate.hover icon="user-group" href="/vendors">Vendors</flux:navlist.item>
                <flux:navlist.item wire:navigate.hover icon="users" href="/clients">Clients</flux:navlist.item>

                <flux:navlist.group expandable heading="Timesheets" class="hidden lg:grid">
                    <flux:navlist.item wire:navigate.hover href="/hours/create" icon="clock">Hours</flux:navlist.item>
                    <flux:navlist.item wire:navigate.hover href="/timesheets" icon="document-currency-dollar">Timesheets</flux:navlist.item>
                    @can('viewPayment', App\Models\Timesheet::class)
                        <flux:navlist.item wire:navigate.hover href="/timesheets/payments" icon="currency-dollar">Payments</flux:navlist.item>
                    @endcan
                </flux:navlist.group>

                @if(auth()->user()->id === 1)
                    <flux:navlist.group expandable heading="Global Actions" class="hidden lg:grid">
                        <flux:navlist.item wire:navigate.hover href="/transactions/match_vendor" icon="eye-slash">Match Vendor</flux:navlist.item>
                        <flux:navlist.item wire:navigate.hover href="/transactions/bulk_match" icon="eye-slash">Match Transactions</flux:navlist.item>
                    </flux:navlist.group>
                @endif

                @if(auth()->user()->primary_vendor->pivot->role_id === 1)
                    <flux:navlist.group expandable heading="Settings" class="hidden lg:grid">
                        <flux:navlist.item wire:navigate.hover href="/vendor_docs" icon="eye-slash">Vendor Docs</flux:navlist.item>
                    </flux:navlist.group>
                @endif
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item wire:navigate.hover icon="cog-6-tooth" href="#">Settings</flux:navlist.item>
                <flux:navlist.item wire:navigate.hover icon="information-circle" href="#">Help</flux:navlist.item>
            </flux:navlist>

            <flux:dropdown position="top" align="left">
                <flux:profile name="{{auth()->user()->full_name}}" />

                <flux:menu>
                    <flux:menu.item href="{{route('vendor_selection')}}">Switch Account</flux:menu.item>
                    @can('admin_login_as_user', App\Models\User::class)
                        <flux:menu.item href="{{route('admin_login_as_user')}}">Incognito</flux:menu.item>
                    @endcan

                    <flux:menu.separator />

                    <flux:menu.item href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @fluxScripts
    </body>
</html>
