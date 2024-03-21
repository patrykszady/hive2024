<div>
    <x-cards.wrapper class="{{$view == NULL ? 'w-full px-4 sm:px-6 lg:max-w-2xl lg:px-8 pb-5 mb-1' : ''}}">
        {{-- HEADING --}}
        <x-cards.heading>
            <x-slot name="left">
                <h1>Vendor Insurance Certificates</h1>
            </x-slot>
        </x-cards.heading>
    </x-cards.wrapper>

    <x-cards.wrapper class="{{$view == NULL ? 'w-full px-4 sm:px-6 lg:max-w-2xl lg:px-8 pb-5 mb-1' : ''}}">
        @livewire('vendor-docs.audit-index')
    </x-cards.wrapper>
    {{-- LIST / using List as a table because of mobile layouts vs a table mobile layout --}}

    <div>
        @foreach($vendors as $vendor)
            <livewire:vendor-docs.vendor-docs-card :$vendor :key="$vendor->id" />
        @endforeach
    </div>
</div>
