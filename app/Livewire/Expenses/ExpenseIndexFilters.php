<?php

namespace App\Livewire\Expenses;
use App\Models\Bank;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class ExpenseIndexFilters extends Component
{
    public $banks = [];
    public $bank_account_ids = [];
    public function mount()
    {


        // dd($this->bank_account_ids);
    }

    public function render()
    {
        return view('livewire.expenses.expense-index-filters');
    }
}
