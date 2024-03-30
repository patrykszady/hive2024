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

    public $vendors = [];
    public $all_vendors = [];
    public $existing_vendors = [];
    public $distributions = [];

    public $showModal = FALSE;

    protected $listeners = ['newMatch', 'updateMatch'];

    public function mount($distributions, $vendors)
    {
        $this->existing_vendors = $vendors->toArray();
        $this->distributions = $distributions;

        $transactions =
            Transaction::whereHas('vendor')->whereDoesntHave('expense')->whereNull('check_number')->whereNotNull('posted_date')->where('posted_date', '<', today()->subDays(3)->format('Y-m-d'))
                ->get()->groupBy('vendor_id');

        $expenses_no_project =
            Expense::whereHas('vendor')->whereDoesntHave('splits')->where('project_id', "0")->whereNull('distribution_id')
                ->get()->groupBy('vendor_id');

        $this->all_vendors = Vendor::whereIn('id', $vendors->toArray())->orWhereIn('id', $transactions->keys())->orWhereIn('id', $expenses_no_project->keys())->where('business_type', 'Retail')->orderBy('business_name')->get();
        $this->vendors = $this->all_vendors;
    }

    public function updated($field, $value)
    {
        if($field == 'form.any_amount' && $value == TRUE){
            $this->form->amount = NULL;
            $this->form->amount_type = NULL;
        }elseif($field == 'form.any_amount' && $value == FALSE){
            $this->form->amount_type = '=';
        }

        $this->validateOnly($field);
    }

    public function newMatch()
    {
        $this->form->reset();
        $this->vendors = $this->all_vendors->whereNotIn('id', $this->existing_vendors);
        $this->showModal = TRUE;
    }

    public function updateMatch(TransactionBulkMatch $match)
    {
        $this->form->reset();
        $this->vendors = $this->all_vendors->whereIn('id', $this->existing_vendors);
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
