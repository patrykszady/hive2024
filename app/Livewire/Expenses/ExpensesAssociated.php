<?php

namespace App\Livewire\Expenses;
use App\Models\Expense;

use Livewire\Component;

class ExpensesAssociated extends Component
{
    public Expense $expense;
    public $associate_expense = '';
    public $expenses = [];
    public $showModal = FALSE;

    protected $listeners = ['addAssociatedExpense'];

    public function rules()
    {
        return [
            'associate_expense' => 'required',
        ];
    }

    public function mount()
    {

    }

    public function updated($field)
    {
        // dd($this);
    }

    public function addAssociatedExpense(Expense $expense)
    {
        $this->expense = $expense;
        // associated_expenses
        $this->expenses =
            Expense::search($expense->amount)
                ->orderBy('date', 'desc')
                ->get()
                ->whereNotIn('id', array_merge(!$expense->associated->isEmpty() ? $expense->associated_expenses->pluck('id')->toArray() : [], [$expense->id]));
                // ->each(function($this_expense, $key) {
                //     $this_expense->title = money($this_expense->amount);
                //     $this_expense->desc = $this_expense->date->format('m/d/Y') . ' | ' . $this_expense->vendor->name;
                // });
                // ->whereBetween('date', ['2024-01-01', '2024-08-08']);

        $this->showModal = TRUE;
    }

    public function save()
    {
        $this->expense->parent_expense_id = $this->associate_expense;
        $this->expense->save();

        $this->dispatch('refreshComponent')->to('expenses.expense-show');
        $this->showModal = FALSE;
        $this->dispatch('notify',
            type: 'success',
            content: 'Expenses Associated',
            route: 'expenses/' . $this->associate_expense
        );
    }

    public function render()
    {
        return view('livewire.expenses.associated');
    }
}
