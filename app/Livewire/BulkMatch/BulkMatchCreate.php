<?php

namespace App\Livewire\BulkMatch;

use App\Models\Vendor;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\TransactionBulkMatch;

use App\Livewire\Forms\BulkMatchForm;

use Livewire\Component;

class BulkMatchCreate extends Component
{
    public BulkMatchForm $form;

    public $new_vendors = [];
    public $existing_vendors = [];
    public $distributions = [];
    public $new_vendor = NULL;
    // public $split = FALSE;
    public $showModal = FALSE;

    protected $listeners = ['newMatch', 'updateMatch'];

    // public function rules()
    // {
    //     return [
    //         'split' => 'nullable'
    //     ];
    // }

    public function mount($distributions, $vendors)
    {
        $this->distributions = $distributions;

        $transactions =
            Transaction::whereHas('vendor')->whereDoesntHave('expense')->whereNull('check_number')->whereNotNull('posted_date')->where('posted_date', '<', today()->subDays(3)->format('Y-m-d'))
                ->get()->groupBy('vendor_id');

        $expenses_no_project =
            Expense::whereHas('vendor')->whereDoesntHave('splits')->where('project_id', "0")->whereNull('distribution_id')
                ->get()->groupBy('vendor_id');

        $this->new_vendors = Vendor::whereIn('id', $transactions->keys())->orWhereIn('id', $expenses_no_project->keys())->where('business_type', 'Retail')->orderBy('business_name')->get();
        $this->existing_vendors = Vendor::whereIn('id', $vendors)->get();
    }

    public function updated($field, $value)
    {
        if($field == 'form.any_amount' && $value == TRUE){
            $this->form->amount = NULL;
            $this->form->amount_type = NULL;
        }elseif($field == 'form.any_amount' && $value == FALSE){
            $this->form->amount_type = '=';
        }

        if($field == 'form.vendor_id' && $value != NULL && !isset($this->form->match)){
            $this->new_vendor = Vendor::findOrFail($value);
            $this->new_vendor->vendor_transactions =
                $this->new_vendor->transactions()
                ->whereDoesntHave('expense')
                ->whereDoesntHave('check')
                ->orderBy('amount', 'DESC')
                ->get()
                ->groupBy('amount')
                ->values()
                //converts to array?
                ->toBase();

            $this->new_vendor->vendor_expenses =
                $this->new_vendor->expenses()
                ->whereDoesntHave('splits')
                ->where('project_id', "0")
                ->whereNull('distribution_id')
                ->orderBy('amount', 'DESC')
                ->get()
                ->groupBy('amount')
                ->toBase();
        }elseif($field == 'form.vendor_id' && $value == NULL && !isset($this->form->match)){
            $this->new_vendor = NULL;
        }

        $this->validateOnly($field);
    }

    public function newMatch()
    {
        $this->new_vendor = NULL;
        $this->form->reset();
        $this->showModal = TRUE;
    }

    public function updateMatch(TransactionBulkMatch $match)
    {
        $this->new_vendor = NULL;
        $this->form->reset();
        $this->form->setMatch($match);
        $this->showModal = TRUE;
    }

    public function save()
    {
        $this->form->store();
        //refresh main component of transactions/bulk_match
        $this->showModal = FALSE;
        $this->dispatch('notify',
            type: 'success',
            content: 'Match Updated'
        );
    }

    public function render()
    {
        $this->authorize('viewAny', TransactionBulkMatch::class);
        return view('livewire.bulk-match.form');
    }
}
