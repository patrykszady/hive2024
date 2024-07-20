<div {{ $attributes->merge(['class' => 'mx-auto']) }}>
    {{-- overflow-hidden --}}
    <div class="bg-white shadow-md sm:rounded-lg">
        {{$slot}}
    </div>
</div>
