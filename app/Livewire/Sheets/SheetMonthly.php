<?php

namespace App\Livewire\Sheets;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Timesheet;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Livewire\Component;

class SheetMonthly extends Component
{
    public $months = [];
    // public $monthly_payments = [];
    // public $monthly_expenses = [];
    // public $monthly_timesheets = [];
    // public $monthly_total_expenses = [];

    public function mount()
    {
        //Carbon::parse('2024-02-29');
        $end_date = Carbon::today();
        $start_date = $end_date->copy()->subMonths(11);

        // Create a period between the start and end dates
        $period = CarbonPeriod::create($start_date, '1 month', $end_date);

        foreach($period as $month){
            $this->months[$month->format('M y')] = [];
        }

        $this->months = array_reverse($this->months);

        $monthly_payments =
            Payment::
                whereBetween('date', [$start_date, $end_date])
                ->orderBy('date', 'DESC')
                ->get()
                ->groupBy(function ($payment) {
                    return $payment->date->format('M y');
                })
                ->toBase();

        foreach($monthly_payments as $month => $payments){
            $this->months[$month]['monthly_payments'] = $payments;
        }

        $monthly_expenses =
            Expense::
                whereBetween('date', [$start_date, $end_date])
                ->orderBy('date', 'DESC')
                ->get()
                ->groupBy(function ($expense) {
                    return $expense->date->format('M y');
                })
                ->toBase();

        foreach($monthly_expenses as $month => $expenses){
            $this->months[$month]['monthly_expenses'] = $expenses;
        }

        $monthly_timesheets =
            Timesheet::
                whereHas('hours', function ($query) use($start_date, $end_date) {
                    return $query->whereBetween('date', [$start_date, $end_date]);
                })
                ->orderBy('date', 'DESC')
                ->get()
                ->groupBy(function ($timesheet) {
                    return $timesheet->date->format('M y');
                })
                ->toBase();

        foreach($monthly_timesheets as $month => $timesheets){
            $this->months[$month]['monthly_timesheets'] = $timesheets;
        }

        // dd($this->months['Mar 24']);
        // foreach($this->months as $month){
        //     $this->monthly_total_expenses[$month] = (isset($this->monthly_timesheets[$month]) ? $this->monthly_timesheets[$month]->sum('amount') : '0') + (isset($this->monthly_expenses[$month]) ? $this->monthly_expenses[$month]->sum('amount') : '0');
        // }
    }

    public function render()
    {
        return view('livewire.sheets.monthly');
    }
}
