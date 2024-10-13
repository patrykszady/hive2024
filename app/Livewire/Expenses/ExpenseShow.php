<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Models\Project;
use App\Models\User;

use Livewire\Component;
use Livewire\Attributes\Title;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpenseShow extends Component
{
    use AuthorizesRequests;

    public Expense $expense;
    public $receipt = NULL;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        // dd($this->expense->transactions);
        $this->receipt = $this->expense->receipts()->latest()->first();
    }

    #[Title('Expense')]
    public function render()
    {
        $this->authorize('view', $this->expense);

        return view('livewire.expenses.show');
    }
}
