<?php

namespace App\Livewire\Expenses;

use Livewire\Component;

use App\Models\Expense;
use App\Models\ExpenseSplits;

// use App\Livewire\Forms\ExpenseSplitForm;

class ExpenseSplitsCreate extends Component
{
    // public ExpenseSplitForm $form;
    public Expense $expense;
    //keep track of expense_splits.*.amount sum
    public $expense_splits = [];
    public $expense_line_items = [];
    public $splits_count = 0;
    public $splits_total = 0;
    public $expense_total = 0;

    public $projects;
    public $distributions;

    public $modal_show = FALSE;

    protected $listeners = ['refreshComponent' => '$refresh', 'addSplits', 'addSplit', 'removeSplit', 'resetSplits'];

    public function rules()
    {
        return [
            'expense_splits.*.amount' => 'required|numeric|regex:/^-?\d+(\.\d{1,2})?$/|not_in:0',
            'expense_splits.*.project_id' => 'required',
            'expense_splits.*.reimbursment' => 'nullable',
            'expense_splits.*.note' => 'nullable',
            'expense_splits.*.items.*.checkbox' => 'nullable',
        ];
    }

    // public function updatedExpenseSplits($field, $key){
    //     dd($key);
    // }
    public function updated($field, $value)
    {
        if(substr($field, 0, 14) == 'expense_splits' && substr($field, -8) == 'checkbox'){
            //item belongs to a split (other splits should have this item disabled)
            $matches = [];
            preg_match_all('/\d+/', $field, $matches);
            $index_split = $matches[0][0];

            if($value == true){
                $this->expense_line_items->items[$matches[0][1]]->split_index = $index_split;
            }else{
                $this->expense_line_items->items[$matches[0][1]]->split_index = NULL;
            }

            //need to account for tax
            $items = collect($this->expense_line_items->items);
            $tax_rate = round($this->expense_line_items->total_tax / $this->expense_line_items->subtotal, 3);
            $tax_rate = 1 + $tax_rate;
            $expense_total = $this->expense_line_items->total;

            $this->expense_splits->transform(function ($split, $key) use ($items, $tax_rate, $expense_total){
                $items_total = $items->where('split_index', $key)->whereNotNull('split_index')->sum('price_total');
                $total_with_tax = $items_total * $tax_rate;

                //if last item without amount? check total...
                //last one. Adjust a penny $0.01 if $expense->amount != getSplitsSumProperty
                if($items->whereNull('split_index')->count() == 0){
                    // dd($this->getSplitsSumProperty());
                    // $difference = $expense_total - ($this->getSplitsSumProperty() + $split['amount']);
                    // dd($difference);
                    $split['amount'] = round($total_with_tax, 2);
                    // $this->splits_total = collect($this->expense_splits)->where('amount', '!=', '')->sum('amount');
                    // $split['amount'] = $this->getSplitsSumProperty();
                    // dd([$expense_total, ]);
                    // dd($expense_total - $this->getSplitsSumProperty());
                    // dd($split['amount'] + $this->getSplitsSumProperty());
                    //$this->getSplitsSumProperty()
                    // $difference = $this->getSplitsSumProperty();
                    // dd($difference);

                }else{
                    $split['amount'] = round($total_with_tax, 2);
                }

                return $split;
            });
        }

        $this->validateOnly($field);
    }

    public function getSplitsSumProperty()
    {
        $this->splits_total = collect($this->expense_splits)->where('amount', '!=', '')->sum('amount');
        return round($this->expense_total - $this->splits_total, 2);
    }

    public function addSplits($expense_total, Expense $expense)
    {
        $this->expense = $expense;
        $receipt = $expense->receipts()->latest()->first();

        //!is_null($receipt->receipt_items->items
        if(!is_null($receipt) && !is_null($receipt->receipt_items)){
            $this->expense_line_items = $receipt->receipt_items;

            $items = [];
            foreach($this->expense_line_items->items as $item_index => $line_item){
                $items[$item_index] = array('checkbox' => false);
            }
        }else{
            $items = NULL;
        }

        $this->expense_total = $expense_total;

        if(!$expense->splits->isEmpty()){
            $this->expense_splits = $expense->splits;
        }elseif(is_array($this->expense_splits) && !empty($this->expense_splits)){
            $this->expense_splits = collect($this->expense_splits);
        }elseif(!is_array($this->expense_splits)){
            if($this->expense_splits->isEmpty()){
                $this->expense_splits = collect();
            }
        }else{
            $this->expense_splits = collect();
        }

        //if splits isset / comign from Expense.Update form.. otherwire
        if($this->expense_splits->isEmpty()){
            $this->expense_splits->push(['amount' => NULL, 'project_id' => NULL, 'items' => $items]);
            $this->expense_splits->push(['amount' => NULL, 'project_id' => NULL, 'items' => $items]);
            $this->splits_count = 2;
        }else{
            foreach($this->expense_splits as $split_index => $split){
                if(isset($split->receipt_items)){
                    $split->items = $split->receipt_items;
                    foreach($split->items as $item_index => $item){
                        if($item['checkbox'] == true){
                            $this->expense_line_items->items[$item_index]->split_index = $split_index;
                        }
                    }
                }
            }

            $this->splits_count = count($this->expense_splits) - 1;
        }

        foreach($this->expense_splits as $index => $split){
            if($split['project_id'] == NULL && isset($split['distribution_id'])){
                $this->expense_splits[$index]['project_id'] = 'D:' . $split['distribution_id'];
            }
        }

        $this->getSplitsSumProperty();
        $this->modal_show = TRUE;
    }

    public function addSplit()
    {
        $receipt = $this->expense->receipts()->latest()->first();

        if(!is_null($receipt) && !is_null($receipt->receipt_items->items)){
            $this->expense_line_items = $receipt->receipt_items;

            foreach($this->expense_line_items->items as $item_index => $line_item){
                $items[$item_index] = array('checkbox' => false);
            }
        }else{
            $items = NULL;
        }

        $this->expense_splits->push(['amount' => NULL, 'project_id' => NULL, 'items' => $items]);

        $this->splits_count = $this->splits_count + 1;
    }

    public function removeSplit($index)
    {
        $this->splits_count = $this->splits_count - 1;

        if(isset($this->expense_splits[$index]['id'])){
            $split_to_remove = ExpenseSplits::findOrFail($this->expense_splits[$index]['id']);
            $split_to_remove->delete();
        }

        unset($this->expense_splits[$index]);
        unset($this->expense_splits[$index]);
    }

    public function resetSplits()
    {
        $this->splits_count = 0;
        $this->splits_total = 0;
        $this->expense_total = 0;
        $this->expense_splits = [];
        $this->expense_line_items = [];
    }

    public function split_store()
    {
        // dd($this->expense_splits);
        $this->validate();

        if(round($this->expense_total - $this->splits_total, 2) != 0.0){
            $this->addError('expense_splits_total_match', 'Expense Amount and Splits Amounts must match');
        }else{
            //send all SPLITS data back to ExpenseForm view
            //send back to ExpenseForm... all validated and tested here
            $this->dispatch('hasSplits', $this->expense_splits)->to(ExpenseCreate::class);
            $this->modal_show = FALSE;
            // $this->expense_splits = [];
            // $this->resetSplits();
        }
    }

    public function render()
    {
        $view_text = [
            'button_text' => 'Save Splits',
            'form_submit' => 'split_store',
        ];

        return view('livewire.expenses.splits-form', [
            'view_text' => $view_text,
        ]);
    }
}
