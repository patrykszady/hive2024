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

    public $split = FALSE;
    public $splits_count = 0;
    public $bulk_splits = [];

    public $view_text = [
        'card_title' => 'Add New Automatic Bulk Match',
        'button_text' => 'Create Bulk Match',
        'form_submit' => 'save',
    ];

    protected $listeners = ['newMatch', 'updateMatch'];

    public function rules()
    {
        return [
            'split' => 'nullable'
        ];
    }

    public function mount($distributions, $vendors)
    {
        // dd($this->split);
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

        // if SPLIT checked vs if unchecked
        // if($field == 'split'){
        //     if($this->split == TRUE){
        //         $this->form->distribution_id = NULL;
        //     }else{
        //         $this->bulk_splits = [];
        //     }
        // }

        // $this->validate();
        $this->validateOnly($field);
    }

    public function bulkSplits()
    {
        $this->bulk_splits = collect();
        $this->bulk_splits->push(['amount' => NULL, 'amount_type' => '$', 'distribution_id' => NULL]);
        $this->bulk_splits->push(['amount' => NULL, 'amount_type' => '$', 'distribution_id' => NULL]);
        $this->splits_count = 2;
    }

    public function addSplit()
    {
        $this->splits_count = $this->splits_count + 1;
        $this->bulk_splits->push(['amount' => NULL, 'amount_type' => '$', 'distribution_id' => NULL]);
    }

    public function removeSplit($index)
    {
        $this->splits_count = $this->splits_count - 1;
        unset($this->bulk_splits[$index]);
    }

    public function newMatch()
    {
        $this->new_vendor = NULL;
        $this->split = FALSE;
        $this->splits_count = 0;
        $this->bulk_splits = [];
        $this->form->reset();
        $this->showModal = TRUE;
    }

    public function updateMatch(TransactionBulkMatch $match)
    {
        $this->new_vendor = NULL;
        $this->split = FALSE;
        $this->splits_count = 0;
        $this->bulk_splits = [];
        $this->form->reset();
        $this->form->setMatch($match);

        if(isset($match->options['splits'])){
            $this->split = TRUE;
            $this->splits_count = count($match->options['splits']);
            $this->bulk_splits = $match->options['splits'];
        }

        $this->view_text = [
            'card_title' => 'Edit New Automatic Bulk Match',
            'button_text' => 'Edit Bulk Match',
            'form_submit' => 'edit',
        ];

        $this->showModal = TRUE;
    }

    public function remove()
    {
        $this->form->match->delete();

        $this->dispatch('notify',
            type: 'success',
            content: 'Match Removed'
        );
    }

    public function edit()
    {
        $this->form->update();
        //refresh main component of transactions/bulk_match
        $this->showModal = FALSE;
        $this->dispatch('notify',
            type: 'success',
            content: 'Match Updated'
        );
    }

    public function save()
    {
        $this->form->store();
        $this->showModal = FALSE;
        $this->dispatch('notify',
            type: 'success',
            content: 'Match Saved'
        );
    }

    public function render()
    {
        $this->authorize('viewAny', TransactionBulkMatch::class);
        return view('livewire.bulk-match.form');
    }
}
