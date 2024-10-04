<div>
    <div class="grid max-w-xl grid-cols-5 gap-4 xl:relative lg:max-w-5xl sm:px-6">
        <div class="col-span-5 space-y-4 lg:col-span-2 lg:h-32 lg:sticky lg:top-5">
            {{-- CHECK DETAILS --}}
            <x-lists.details_card>
                {{-- HEADING --}}
                <x-slot:heading>
                    <div>
                        <flux:heading size="lg" class="mb-0">Check Details</flux:heading>
                    </div>

                    <flux:button
                        wire:click="$dispatchTo('checks.check-create', 'editCheck', { check: {{$check->id}}})"
                        size="sm"
                        >
                        Edit Check
                    </flux:button>
                </x-slot>

                {{-- DETAILS --}}
                <x-lists.details_list>
                    <x-lists.details_item title="Amount" detail="{{money($check->amount)}}" />
                    <x-lists.details_item title="Payee" detail="{{$check->owner}}" href="{{$check->vendor_id ? route('vendors.show', $check->vendor->id) : '#'}}" />
                    <x-lists.details_item title="Date" detail="{{$check->date->format('m/d/Y')}}" />
                    <x-lists.details_item title="Type" detail="{{$check->check_type}}" />

                    @if($check->bank_account)
                        <x-lists.details_item title="Bank" detail="{{$check->bank_account->getNameAndType()}}" />
                    @endif

                    @if($check->check_number)
                        <x-lists.details_item title="Check Number" detail="{{$check->check_number}}" />
                    @endif
                </x-lists.details_list>
            </x-lists.details_card>

            {{-- CHECK TRANSACTIONS --}}
            @if(!$check->transactions->isEmpty())
                <flux:card class="space-y-2">
                    <flux:heading size="lg" class="mb-0">Transactions</flux:heading>
                    <flux:separator variant="subtle" />

                    <div class="space-y-6">
                        {{-- wire:loading.class="opacity-50 text-opacity-40" --}}
                        <flux:table>
                            <flux:columns>
                                <flux:column>Amount</flux:column>
                                <flux:column>Date</flux:column>
                                <flux:column>Bank</flux:column>
                                <flux:column>Account</flux:column>
                                <flux:column>Desc</flux:column>
                            </flux:columns>

                            <flux:rows>
                                @foreach ($check->transactions as $transaction)
                                    <flux:row :key="$transaction->id">
                                        <flux:cell variant="strong">
                                            {{ money($transaction->amount) }}
                                        </flux:cell>
                                        <flux:cell>{{ $transaction->transaction_date->format('m/d/Y') }}</flux:cell>
                                        <flux:cell>{{ $transaction->bank_account->bank->name }}</flux:cell>
                                        <flux:cell>{{ $transaction->bank_account->account_number }}</flux:cell>
                                        <flux:cell>{{ $transaction->plaid_merchant_description }}</flux:cell>
                                    </flux:row>
                                @endforeach
                            </flux:rows>
                        </flux:table>
                    </div>
                </flux:card>
            @endif
        </div>

        <div class="col-span-5 space-y-2 lg:col-span-3">
            {{-- THIS CHECK USER PAID TIMESHEETS --}}
            @if(!$weekly_timesheets->isEmpty())
            <x-cards class="col-span-5 lg:col-span-3 lg:col-start-3">
                <x-cards.heading>
                    <x-slot name="left">
                        <h1><b>{{$check->user->first_name}}</b>'s Timesheets</h1>
                    </x-slot>
                </x-cards.heading>

                <x-lists.ul>
                    @foreach($weekly_timesheets->groupBy('date') as $weekly_project_timesheets)
                        <x-lists.search_li
                            {{-- wire:click="$dispatch('timesheetWeek')" --}}
                            :no_hover=true
                            href="{{route('timesheets.show', $weekly_project_timesheets->first()->id)}}"
                            :line_title="'Week of ' . $weekly_project_timesheets->first()->date->startOfWeek()->toFormattedDateString() . ' | ' . money($weekly_project_timesheets->sum('amount'))"
                            :bubble_message="'Timesheets'"
                            {{-- :class="'pointer-events-none'" --}}
                            >
                        </x-lists.search_li>

                        {{-- 7-15-2022 Each foreach li shoud be a checkbox wherever it is clicked like an href --}}
                        @foreach($weekly_project_timesheets as $key => $project_timesheet)
                            @php
                                $line_details = [
                                    1 => [
                                        'text' => $project_timesheet->hours,
                                        'icon' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z'
                                        ],
                                    2 => [
                                        'text' => $project_timesheet->project->name,
                                        'icon' => 'M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z'
                                        ],
                                    ];

                                // $checkbox = [
                                //     'wire_click' => "like($project_timesheet->id)",
                                //     'id' => "$key",
                                //     'name' => "project_timesheet",
                                // ]
                            @endphp

                            <x-lists.search_li
                                {{-- wire:click="$dispatch('timesheetWeek')" --}}
                                {{-- :line_details="$line_details" --}}
                                href="{{route('projects.show', $project_timesheet->project->id)}}"
                                :line_title="money($project_timesheet->amount) . ' | ' . $line_details[1]['text'] . ' Hours | ' . $line_details[2]['text']"
                                :bubble_message="'Project'"
                                {{-- :checkbox="$checkbox" --}}
                                >
                            </x-lists.search_li>
                        @endforeach
                    @endforeach
                </x-lists.ul>
            </x-cards>
            @endif

            {{-- THIS CHECK USER PAID EMPLOYEE TIMESHEETS --}}
            @if(!$employee_weekly_timesheets->isEmpty())
            <x-cards class="col-span-5 lg:col-span-3 lg:col-start-3">
                <x-cards.heading>
                    <x-slot name="left">
                        <h1>Employee Paid Timesheets</h1>
                    </x-slot>
                </x-cards.heading>

                <x-lists.ul>
                    @foreach($employee_weekly_timesheets as $user_id => $employee_timesheet_weeks)
                        {{--  . ' | ' . $employee_timesheets_total[3] --}}
                        <x-lists.search_li
                            :no_hover=true
                            :line_title="$employee_timesheet_weeks->first()->first()->user->full_name"
                            :bubble_message="'Team Member'"
                            {{-- :class="'pointer-events-none'" --}}
                            >
                        </x-lists.search_li>

                        @foreach($employee_timesheet_weeks as $week => $employee_timesheet_week)
                            <x-lists.search_li
                                href="{{route('timesheets.show', $employee_timesheet_week->first()->id)}}"
                                :no_hover=true
                                :line_title="'Week of ' . $employee_timesheet_week->first()->date->toFormattedDateString() . ' | ' . money($employee_timesheet_week->sum('amount'))"
                                :bubble_message="'Timesheet'"
                                {{-- :class="'pointer-events-none'" --}}
                                >
                            </x-lists.search_li>
                            @foreach($employee_timesheet_week as $key => $employee_timesheet_week_project)
                                @php
                                    $line_details = [
                                        1 => [
                                            'text' => $employee_timesheet_week_project->hours,
                                            'icon' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z'
                                            ],
                                        2 => [
                                            'text' => $employee_timesheet_week_project->project->name,
                                            'icon' => 'M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z'
                                            ],
                                        ];

                                    // $checkbox = [
                                    //     'wire_click' => "like($project_timesheet->id)",
                                    //     'id' => "$key",
                                    //     'name' => "project_timesheet",
                                    // ]
                                @endphp

                                <x-lists.search_li
                                    {{-- wire:click="$dispatch('timesheetWeek')" --}}
                                    {{-- :line_details="$line_details" --}}
                                    href="{{route('projects.show', $employee_timesheet_week_project->project->id)}}"
                                    :line_title="money($employee_timesheet_week_project->amount)  . ' | ' . $line_details[1]['text'] . ' Hours | ' . $line_details[2]['text']"
                                    :bubble_message="'Project'"
                                    >
                                </x-lists.search_li>
                            @endforeach
                        @endforeach
                    @endforeach
                </x-lists.ul>
            </x-cards>
            @endif

            {{-- THIS CHECK VENDOR PAID EXPENSES --}}
            {{-- @if(!is_null($vendor_paid_expenses))
            <x-cards.wrapper class="col-span-4 lg:col-span-2 lg:col-start-3">
                <x-cards.heading>
                    <x-slot name="left">
                        <h1>Vendor Paid Expenses</h1>
                    </x-slot>
                </x-cards.heading>

                <x-lists.ul>
                    @foreach($vendor_paid_expenses as $paid_expense)
                        <x-lists.search_li
                            :line_title="money($paid_expense->amount) . ' | ' . $paid_expense->project->name"
                            href="{{route('expenses.show', $paid_expense->id)}}"
                            :bubble_message="'Expense'"
                            >
                        </x-lists.search_li>
                    @endforeach
                </x-lists.ul>
            </x-cards.wrapper>
            @endif --}}

            {{-- THIS CHECK EXPENSES EXPENSES --}}
            @if(!$vendor_expenses->isEmpty())
                <div class="col-span-5 lg:col-span-3 lg:col-start-3">
                    <livewire:expenses.expense-index :check="$check->id" :view="'checks.show'"/>
                </div>
            @endif

            {{-- THIS CHECK USER PAID EXPENSES --}}
            @if(!$user_paid_expenses->isEmpty())
            <x-cards class="col-span-5 lg:col-span-3 lg:col-start-3">
                <x-cards.heading>
                    <x-slot name="left">
                        <h1>Paid Expenses</h1>
                    </x-slot>
                </x-cards.heading>

                <x-lists.ul>
                    @foreach($user_paid_expenses as $paid_expense)
                        <x-lists.search_li
                            {{-- wire:click="$dispatch('timesheetWeek')" --}}
                            {{-- :line_details="$line_details" --}}
                            :line_title="money($paid_expense->amount) . ' | ' . $paid_expense->project->name"
                            href="{{route('expenses.show', $paid_expense->id)}}"
                            href_target="_blank"
                            :bubble_message="'Expense'"
                            {{-- :checkbox="$checkbox" --}}
                            >
                        </x-lists.search_li>
                    @endforeach
                </x-lists.ul>
            </x-cards>
            @endif

            {{-- THIS CHECK DISTRIBUTIONS --}}
            @if(!$user_distributions->isEmpty())
                <x-cards class="col-span-5 lg:col-span-3 lg:col-start-3">
                    <x-cards.heading>
                        <x-slot name="left">
                            <h1>Paid Distrbutions</h1>
                        </x-slot>
                    </x-cards.heading>

                    <x-lists.ul>
                        @foreach($user_distributions as $user_distribution_expense)
                            <x-lists.search_li
                                :href="route('expenses.show', $user_distribution_expense)"
                                :line_title="money($user_distribution_expense->amount) . ' | ' . $user_distribution_expense->distribution->name"
                                :bubble_message="'Distribution'"
                                >
                            </x-lists.search_li>
                        @endforeach
                    </x-lists.ul>
                </x-cards>
            @endif

            {{-- THIS CHECK USER PAID REIMBURESEMENT RECEIPTS FROM ANOTHER EMPLOYEE --}}
            {{-- @if(!$user_paid_reimburesements->isEmpty())
            <x-cards class="col-span-4 lg:col-span-2 lg:col-start-3">
                <x-cards.heading>
                    <x-slot name="left">
                        <h1>Paid Employee Reimbursements</h1>
                    </x-slot>
                </x-cards.heading>

                <x-lists.ul>
                    @foreach($user_paid_reimburesements as $user_distribution_expense)
                        <x-lists.search_li
                            :href="route('expenses.show', $user_distribution_expense)"
                            :line_title="money($user_distribution_expense->amount)"
                            :bubble_message="'Reimbursement'"
                            >
                        </x-lists.search_li>
                    @endforeach
                </x-lists.ul>
            </x-cards>
            @endif --}}

            {{-- THIS CHECK USER PAID REIMBURESEMENT RECEIPTS FROM ANOTHER EMPLOYEE --}}
            @if(!$user_paid_by_reimbursements->isEmpty())
            <x-cards class="col-span-5 lg:col-span-3 lg:col-start-3">
                <x-cards.heading>
                    <x-slot name="left">
                        <h1>Paid Other Employee Reimbursements</h1>
                    </x-slot>
                </x-cards.heading>

                <x-lists.ul>
                    @foreach($user_paid_by_reimbursements as $user_distribution_expense)
                        <x-lists.search_li
                            :href="route('expenses.show', $user_distribution_expense)"
                            href_target="_blank"
                            :line_title="substr($user_distribution_expense->amount, 0, 1) == '-' ? money(substr($user_distribution_expense->amount, 1)) : '-' . money($user_distribution_expense->amount) . ' | ' . $user_distribution_expense->vendor->name . ' | ' . $user_distribution_expense->reimbursment"
                            :bubble_message="'Reimbursement'"
                            >
                        </x-lists.search_li>
                    @endforeach
                </x-lists.ul>
            </x-cards>
            @endif
        </div>
    </div>
    <livewire:checks.check-create />
</div>



