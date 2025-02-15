<?php

namespace App\Livewire\BulkMatch;

use App\Livewire\Forms\BulkMatchForm;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\TransactionBulkMatch;
use App\Models\Vendor;
use Livewire\Component;

class BulkMatchCreate extends Component
{
    public BulkMatchForm $form;

    public $new_vendors = [];

    public $existing_vendors = [];

    public $distributions = [];

    public $new_vendor = null;

    // public $split = FALSE;
    public $showModal = false;

    public $split = false;

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
            'split' => 'nullable',
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
            Expense::whereHas('vendor')->whereDoesntHave('splits')->where('project_id', '0')->whereNull('distribution_id')
                ->get()->groupBy('vendor_id');

        $this->new_vendors = Vendor::whereIn('id', $transactions->keys())->orWhereIn('id', $expenses_no_project->keys())->where('business_type', 'Retail')->orderBy('business_name')->get();
        $this->existing_vendors = Vendor::whereIn('id', $vendors)->get();
    }

    public function updated($field, $value)
    {
        if ($field == 'form.any_amount' && $value == true) {
            $this->form->amount = null;
            $this->form->amount_type = null;
        } elseif ($field == 'form.any_amount' && $value == false) {
            $this->form->amount_type = '=';
        }

        if ($field == 'form.vendor_id' && $value != null && ! isset($this->form->match)) {
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
                    ->where('project_id', '0')
                    ->whereNull('distribution_id')
                    ->orderBy('amount', 'DESC')
                    ->get()
                    ->groupBy('amount')
                    ->toBase();
        } elseif ($field == 'form.vendor_id' && $value == null && ! isset($this->form->match)) {
            $this->new_vendor = null;
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
        $this->bulk_splits->push(['amount' => null, 'amount_type' => '$', 'distribution_id' => null]);
        $this->bulk_splits->push(['amount' => null, 'amount_type' => '$', 'distribution_id' => null]);
        $this->splits_count = 2;
    }

    public function addSplit()
    {
        $this->splits_count = $this->splits_count + 1;
        $this->bulk_splits->push(['amount' => null, 'amount_type' => '$', 'distribution_id' => null]);
    }

    public function removeSplit($index)
    {
        $this->splits_count = $this->splits_count - 1;
        unset($this->bulk_splits[$index]);
    }

    public function newMatch()
    {
        $this->new_vendor = null;
        $this->split = false;
        $this->splits_count = 0;
        $this->bulk_splits = [];
        $this->form->reset();
        $this->showModal = true;
    }

    public function updateMatch(TransactionBulkMatch $match)
    {
        $this->new_vendor = null;
        $this->split = false;
        $this->splits_count = 0;
        $this->bulk_splits = [];
        $this->form->reset();
        $this->form->setMatch($match);

        if (isset($match->options['splits'])) {
            $this->split = true;
            $this->splits_count = count($match->options['splits']);
            $this->bulk_splits = $match->options['splits'];
        }

        $this->view_text = [
            'card_title' => 'Edit New Automatic Bulk Match',
            'button_text' => 'Edit Bulk Match',
            'form_submit' => 'edit',
        ];

        $this->showModal = true;
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
        $this->dispatch('refreshComponent')->to('transactions.bulk-match');
        $this->showModal = false;
        $this->dispatch('notify',
            type: 'success',
            content: 'Match Updated'
        );
    }

    public function save()
    {
        $this->form->store();
        $this->dispatch('refreshComponent')->to('transactions.bulk-match');
        $this->showModal = false;
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
