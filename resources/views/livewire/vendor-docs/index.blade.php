<div>
    <x-cards class="{{$view == NULL ? 'w-full px-4 sm:px-6 lg:max-w-2xl lg:px-8 pb-5 mb-1' : ''}}">
        {{-- HEADING --}}
        <x-cards.heading>
            <x-slot name="left">
                <h1>Vendor Insurance Certificates</h1>
            </x-slot>
        </x-cards.heading>
    </x-cards>

    <x-cards class="{{$view == NULL ? 'w-full px-4 sm:px-6 lg:max-w-2xl lg:px-8 pb-5 mb-1' : ''}}">
        @livewire('vendor-docs.audit-index')
    </x-cards>
    {{-- LIST / using List as a table because of mobile layouts vs a table mobile layout --}}

    <div>
        @foreach($vendors as $vendor)
            <livewire:vendor-docs.vendor-docs-card :$vendor :key="$vendor->id" />
        @endforeach
    </div>

    <livewire:vendor-docs.vendor-doc-create />
</div>
