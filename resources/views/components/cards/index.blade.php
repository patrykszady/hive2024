@aware([
    'accordian' => NULL,
])

<div x-data>
    <div {{isset($accordian) ? 'x-disclosure' : ''}} {{$accordian == "OPENED" ? 'default-open' : ''}} {{ $attributes->merge(['class' => 'mx-auto']) }} >
        <div class="rounded-lg bg-white shadow">
            {{-- overflow-hidden --}}
            <div class="bg-white shadow-md sm:rounded-lg">
                {{$slot}}
            </div>
        </div>
    </div>
</div>
