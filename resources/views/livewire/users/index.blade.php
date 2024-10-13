{{-- 10-05-2024 should be same as VENDOR USERS --}}
<flux:card class="space-y-2 mb-4">
    <div class="flex justify-between">
        <flux:heading size="lg">{{$view_text['card_title']}}</flux:heading>
        {{-- @can('create_client_member', [App\Models\User::class, $client]) --}}
            <flux:button wire:navigate.hover wire:click="add_user" icon="plus" size="sm">{{$view_text['card_title']}}</flux:button>
        {{-- @endcan --}}
    </div>

    <flux:separator variant="subtle" />

    <flux:table>
        <flux:columns>
            <flux:column>Name</flux:column>
            <flux:column>Phone</flux:column>
            <flux:column>Email</flux:column>
            @if ($view === 'vendors.show')
                <flux:column>Role</flux:column>
            @endif
        </flux:columns>

        <flux:rows>
            @foreach ($users as $user)
                <flux:row :key="$user->id">
                    <flux:cell
                        wire:navigate.hover
                        href="{{route('users.show', $user->id)}}"
                        variant="strong"
                        class="cursor-pointer"
                        >
                        {{ $user->full_name }}
                    </flux:cell>
                    <flux:cell>{{ $user->cell_phone }}</flux:cell>
                    <flux:cell>{{ Str::limit($user->email, 8) }}</flux:cell>
                    @if ($view === 'vendors.show')
                        <flux:cell>
                            {{ $user->getVendorRole($vendor->id) }}
                            {{-- <flux:badge inset="top bottom" color="{{$user->getVendorRole($vendor->id) === 'Admin' ? 'cyan' : 'purple'}}">
                                {{ $user->getVendorRole($vendor->id) }}
                            </flux:badge> --}}
                        </flux:cell>
                    @endif
                </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</flux:card>

{{--
    <div
    x-data="{ vendor_info: @entangle('registration') }"
    x-show="vendor_info"
    x-transition.duration.250ms
    >
    <x-cards.footer>
        <button></button>
        <x-cards.button
            wire:click="$dispatchTo('entry.vendor-registration', 'confirmProcessStep', { process_step: 'team_members' })"
            button_color=white
            >
            No Employees
        </x-cards.button>
    </x-cards.footer>
</div> --}}
