<x-cards.wrapper class="w-full px-4 pb-5 mb-1 sm:px-6 lg:max-w-4xl lg:px-8">
    @livewire('lagoon-gantt-chart', ['chartId' => 'uniqueID', 'chartData' => $data, 'height' => 300, 'width' => 400, 'options' => []], key('uniquekey'.now()))
</x-cards.wrapper>
