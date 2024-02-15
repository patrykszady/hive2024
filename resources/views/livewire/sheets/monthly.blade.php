<div>
    <x-cards.wrapper>
        <x-cards.heading>
            <x-slot name="left">
                <h1 class="text-lg">Company Payments & Expenses</h1>
            </x-slot>
        </x-cards.heading>

        <x-cards.body>
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div
                                class="w-full pl-11 sm:pl-7 pr-14"
                                x-data="{
                                    init() {
                                        let chart = new Chart(this.$refs.canvas.getContext('2d'), {
                                            type: 'line',

                                            data: {
                                                labels: [
                                                    @foreach($months as $month)
                                                        '{{$month}}',
                                                    @endforeach
                                                ],
                                                datasets: [
                                                    {
                                                        data: [
                                                            @foreach($monthly_payments as $month_payments)
                                                                {{$month_payments->sum('amount')}},
                                                            @endforeach
                                                        ],
                                                        label: 'Payments',
                                                        borderColor: [
                                                            '#38B000',
                                                        ],
                                                        tension: 0.1
                                                    },
                                                    {
                                                        data: [
                                                            @foreach($monthly_expenses as $month_expenses)
                                                                {{$month_expenses->sum('amount')}},
                                                            @endforeach
                                                        ],
                                                        label: 'Expenses',
                                                        borderColor: [
                                                            '#F2545B',
                                                        ],
                                                        tension: 0.1
                                                    },
                                                    {
                                                        data: [
                                                            @foreach($monthly_timesheets as $month_timesheets)
                                                                {{$month_timesheets->sum('amount')}},
                                                            @endforeach
                                                        ],
                                                        label: 'Timesheets',
                                                        borderColor: [
                                                            '#D64045',
                                                        ],
                                                        tension: 0.1
                                                    },
                                                    {
                                                        data: [
                                                            @foreach($monthly_total_expenses as $month_total_expenses)
                                                                {{$month_total_expenses}},
                                                            @endforeach
                                                        ],
                                                        label: 'Total Expenses',
                                                        borderColor: [
                                                            '#CACFD2',
                                                        ],
                                                        tension: 0.1,
                                                        borderDash: [15,5]
                                                    }
                                                ],
                                            },
                                            options: {
                                                interaction: { intersect: true },
                                                borderWidth: '2',
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                borderColor: 'white',
                                                {{-- scales: { y: { beginAtZero: true }}, --}}
                                                plugins: {
                                                    legend: { position: 'bottom', display: true },
                                                    tooltip: {
                                                        displayColors: true
                                                    }
                                                }
                                            }
                                        })

                                        this.$watch('values', () => {
                                            chart.data.labels = this.labels
                                            chart.data.datasets[0].data = this.values
                                            chart.update()
                                        })
                                    }
                                }"
                                >
                                <div class="h-64 sm:h-96">
                                    <canvas x-ref="canvas" class="bg-transparent rounded-lg"></canvas>
                                </div>
                            </div>
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                    <tr class="divide-x divide-gray-200">
                                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-gray-900 sm:pl-0"></th>
                                        @foreach($months as $month)
                                            <th scope="col" class="px-4 py-3.5 text-left text-sm font-semibold text-gray-900">{{$month}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="divide-x divide-gray-200">
                                        <td class="py-4 pl-4 pr-4 text-sm font-medium text-gray-900 whitespace-nowrap sm:pl-0">Payments</td>

                                        @foreach($monthly_payments as $month_payments)
                                            <td class="p-4 text-sm text-gray-800 whitespace-nowrap">{{money($month_payments->sum('amount'))}}</td>
                                        @endforeach
                                    </tr>

                                    <tr class="divide-x divide-gray-200">
                                        <td class="py-4 pl-4 pr-4 text-sm font-medium text-gray-900 whitespace-nowrap sm:pl-0">Expenses</td>

                                        @foreach($monthly_expenses as $month_expenses)
                                            <td class="p-4 text-sm text-gray-800 whitespace-nowrap">{{money($month_expenses->sum('amount'))}}</td>
                                        @endforeach
                                    </tr>

                                    <tr class="divide-x divide-gray-200">
                                        <td class="py-4 pl-4 pr-4 text-sm font-medium text-gray-900 whitespace-nowrap sm:pl-0">Timesheets</td>

                                        @foreach($monthly_timesheets as $month_timesheets)
                                            <td class="p-4 text-sm text-gray-800 whitespace-nowrap">{{money($month_timesheets->sum('amount'))}}</td>
                                        @endforeach
                                    </tr>

                                    <tr class="divide-x divide-gray-200">
                                        <td class="py-4 pl-4 pr-4 text-sm font-medium text-gray-900 whitespace-nowrap sm:pl-0">TOTAL<br>EXPENSES</td>

                                        @foreach($monthly_total_expenses as $month_total_expenses)
                                            <td class="p-4 text-sm text-gray-800 whitespace-nowrap">{{money($month_total_expenses)}}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </x-cards.body>
    </x-cards.wrapper>
</div>
