<?php

namespace App\Livewire\Timesheets;

use App\Models\BankAccount;
use App\Models\Expense;
use App\Models\User;
use App\Models\Timesheet;
use App\Models\Vendor;

use Livewire\Component;
use Livewire\Attributes\Title;

use App\Livewire\Forms\TimesheetPaymentForm;
// use App\Livewire\Forms\CheckForm;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TimesheetPaymentCreate extends Component
{
    use AuthorizesRequests;

    public TimesheetPaymentForm $form;
    // public CheckForm $check_form;

    public User $user;

    public $weekly_timesheets = [];
    public $employee_weekly_timesheets = [];
    public $user_paid_expenses = [];
    public $user_reimbursement_expenses = [];
    public $user_paid_by_reimbursements = [];

    public $disable_paid_by = FALSE;

    protected function rules()
    {
        return [
            'weekly_timesheets.*.checkbox' => 'nullable',
            'employee_weekly_timesheets.*.checkbox' => 'nullable',
            'user_paid_expenses.*.checkbox' => 'nullable',
            'user_reimbursement_expenses.*.checkbox' => 'nullable',
            'user_paid_by_reimbursements.*.checkbox' => 'nullable',
            'user.via_vendor_back' => 'nullable',
            'user.payee_name' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->user->pivot_user_vendor = $this->user->vendors()->where('vendors.id', auth()->user()->primary_vendor_id)->first()->pivot->via_vendor_id;

        if(!is_null($this->user->pivot_user_vendor)){
            $via_vendor_back = Vendor::withoutGlobalScopes()->findOrFail($this->user->pivot_user_vendor);
            $this->user->payee_name = $via_vendor_back->business_name;
            $this->user->via_vendor_back = auth()->user()->vendor;
        }else{
            $this->user->payee_name = $this->user->full_name;
            $this->user->via_vendor_back = $this->user->vendor;
        }

        $this->weekly_timesheets =
            Timesheet::
                where('user_id', $this->user->id)
                ->whereNull('check_id')
                ->whereNull('paid_by')
                ->whereNull('deleted_at')
                ->orderBy('date', 'DESC')
                ->get()
                ->each(function ($item, $key) {
                    $item->checkbox = true;
                })
                ->keyBy('id');
                // ->groupBy(function($data) {
                //     // ->startOfWeek()->toFormattedDateString()
                //     return $data->date->format('Y-m-d');
                // }, true)
                // ->toBase();

        $this->employee_weekly_timesheets =
                Timesheet::
                    with('user')
                    ->where('paid_by', $this->user->id)
                    ->whereNull('check_id')
                    ->whereNull('deleted_at')
                    ->orderBy('date', 'DESC')
                    ->get()
                    ->each(function ($item, $key) {
                        $item->checkbox = true;
                    })
                    ->keyBy('id');

        $this->user_paid_expenses =
            Expense::
                where('paid_by', $this->user->id)
                ->whereNull('reimbursment')
                ->whereNull('check_id')
                ->orderBy('date', 'DESC')
                ->get()
                ->each(function ($item, $key) {
                    $item->checkbox = true;
                })
                ->keyBy('id');

        $this->user_reimbursement_expenses =
            Expense::
                where('reimbursment', $this->user->id)
                ->whereNull('paid_by')
                ->whereNull('check_id')
                ->orderBy('date', 'DESC')
                ->get()
                ->each(function ($item, $key) {
                    $item->checkbox = true;
                })
                ->keyBy('id');

        $this->user_paid_by_reimbursements =
            Expense::
                where('paid_by', $this->user->id)
                ->whereNotNull('reimbursment')
                ->whereNull('check_id')
                ->orderBy('date', 'DESC')
                ->get()
                ->each(function ($item, $key) {
                    $item->checkbox = true;
                })
                ->keyBy('id');

                // dd($this->user_paid_by_reimbursements->first()->reimbursment);

        if($this->weekly_timesheets->isEmpty()){
            $this->weekly_timesheets = collect();
        }

        if($this->employee_weekly_timesheets->isEmpty()){
            $this->employee_weekly_timesheets = collect();
        }

        if($this->user_paid_expenses->isEmpty()){
            $this->user_paid_expenses = collect();
        }

        if($this->user_reimbursement_expenses->isEmpty()){
            $this->user_reimbursement_expenses = collect();
        }

        if($this->user_paid_by_reimbursements->isEmpty()){
            $this->user_paid_by_reimbursements = collect();
        }

        $this->form->setUser($this->user);
    }

    public function updated($field)
    {
        $this->validateOnly($field);
        // $this->validateOnly('form.bank_account_id');
        // $this->validateOnly('form.paid_by');
    }

    // public function updated($field, $value)
        // {
        //     $this->form->validate();
        // }
        // public function updated($field, $value)
        // {
        //     //reset check and reference if paid_by or check items are changed.
        //     //8-24-2022 - this goes with VendorPaymentForm as well.
        //     if($field == 'check.check_type'){
        //         if($this->check->check_type == 'Check'){
        //             $this->check_input = TRUE;
        //         }else{
        //             $this->check->check_number = NULL;
        //             $this->check_input = FALSE;
        //         }
        //     }

        //     if($field == 'check.paid_by'){
        //         if($value == ""){
        //             $this->check->paid_by = NULL;
        //         }
        //         $this->check->bank_account_id = NULL;
        //         $this->check->check_type = NULL;
        //         $this->check->check_number = NULL;
        //         $this->check_input = FALSE;
        //     }

        //     $this->validateOnly($field);
        // }

    public function getWeeklyTimesheetsTotalProperty()
    {
        $total = 0;
        $confirm_disable = array();
        //weekly_timesheets
        $total += $this->weekly_timesheets->where('checkbox', true)->sum('amount');

        //employee_weekly_timesheets
        $employee_weekly_timesheets_total = $this->employee_weekly_timesheets->where('checkbox', true)->sum('amount');
        if($employee_weekly_timesheets_total != '0.00'){
            $confirm_disable[] = TRUE;
        }
        $total += $employee_weekly_timesheets_total;

        //user_paid_expenses
        $user_paid_expenses_total = $this->user_paid_expenses->where('checkbox', true)->sum('amount');
        if($user_paid_expenses_total != '0.00'){
            $confirm_disable[] = TRUE;
        }
        $total += $user_paid_expenses_total;

        //user_reimbursement_expenses
        $total -= $this->user_reimbursement_expenses->where('checkbox', true)->sum('amount');

        //user_paid_by_reimbursements
        $user_paid_by_reimbursements = $this->user_paid_by_reimbursements->where('checkbox', true)->sum('amount');
        if($user_paid_by_reimbursements != '0.00'){
            $confirm_disable[] = TRUE;
        }
        $total += $user_paid_by_reimbursements;

        if(in_array('true', $confirm_disable)){
            $this->disable_paid_by = TRUE;
            $this->form->paid_by = NULL;
        }else{
            $this->disable_paid_by = FALSE;
        }

        return $total;
    }

    public function save()
    {
        // $this->authorize('create', Expense::class);

        //validate Pay User Total Check is greater than $0 / $this->weekly_timesheets has at least one Item in Collection
        if($this->weekly_timesheets_total == 0){
            $this->addError('weekly_timesheets_total', 'Payment needs at least one Timesheet');
        }else{
            $redirect_route = $this->form->store();

            // dd($redirect_route);
            if($redirect_route == 'timesheets'){
                return redirect()->route('timesheets.payments');
            }else{
                $check = $redirect_route;
                // $expenses = $check->expenses;
                // foreach($expenses as $expense){
                //     if(is_numeric($expense->reimbursment)){
                //         $expense->amount = $expense->amount;
                //     }
                // }

                //$check->expenses->whereNotNull('paid_by')->whereNull('reimbursment')->sum('amount')
                // $check->amount = $check->timesheets->sum('amount') + $expenses->sum('amount');
                $check->amount = $check->amount;
                $check->save();

                return redirect()->route('checks.show', $check->id);
            }
        }
    }

    #[Title('Timesheets Payment')]
    public function render()
    {
        $this->authorize('viewPayment', Timesheet::class);

        $view_text = [
            'card_title' => 'Create Daily Hours',
            'button_text' => 'Pay ' . $this->user->first_name,
            'form_submit' => 'save',
        ];

        $employees = auth()->user()->vendor->users()->where('is_employed', 1)->whereNot('users.id', $this->user->id)->get();

        //09-05-2023 BankAccount::scopeIsActicce(Checking)
        $bank_accounts = BankAccount::with('bank')->where('type', 'Checking')
            ->whereHas('bank', function ($query) {
                return $query->whereNotNull('plaid_access_token');
            })->get();
        //07/16/2022: what about distributions.. would distributions ever end up here?
        return view('livewire.timesheets.payment-form', [
            'view_text' => $view_text,
            'bank_accounts' => $bank_accounts,
            'employees' => $employees,
        ]);
    }
}
