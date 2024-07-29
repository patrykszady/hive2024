<?php

namespace App\Livewire\Expenses;

use App\Models\Vendor;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Bank;
use App\Models\Distribution;
use App\Models\Transaction;

use Livewire\Component;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;

use Livewire\WithPagination;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Lazy]
class ExpenseIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    public $amount = '';

    public $expense_vendor = '';
    public $vendors = [];

    public $project = '';
    public $projects = [];

    public $distributions = [];

    public $bank = NULL;
    public $banks = [];
    public $bank_account_ids = [];
    public $bank_owners = [];
    public $bank_owner = NULL;

    public $status = NULL;

    public $view = NULL;

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $queryString = [
        'amount' => ['except' => ''],
        'project' => ['except' => ''],
        'expense_vendor' => ['except' => ''],
        'bank' => ['except' => ''],
        'bank_owner' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updating($field)
    {
        // $this->resetPage();
        $this->resetPage('expenses-page');
        // $this->resetPage('transactions_page');
    }

    public function updated($field, $value)
    {
        // && $value == 'NO_PROJECT'
        // if($field == 'project'){
        //     $this->expense_vendor = NULL;
        // }

        // if($field == 'vendor'){
        //     $this->project = NULL;
        // }
    }

    public function mount()
    {
        // $this->resetPage('expenses-page');
        // $this->banks = Bank::with('accounts')->get()->groupBy('plaid_ins_id')->toBase();
        $this->vendors = Vendor::whereHas('expenses')->orWhereHas('transactions')->orderBy('business_name')->get();
        $this->projects = Project::whereHas('expenses')->orderBy('created_at', 'DESC')->get();
        // $this->distributions = Distribution::all();
    }

    // public function placeholder(array $params = [])
    // {
    //     return view('livewire.placeholders.skeleton', $params);
    // }

    #[Title('Expenses')]
    public function render()
    {
        $this->authorize('viewAny', Expense::class);

        if($this->view == NULL){
            $paginate_number = 8;
        }else{
            $paginate_number = 5;
        }

        $expenses =
            Expense::search($this->amount)
                ->when(!empty($this->expense_vendor), function ($query, $item) {
                    return $query->where('vendor_id', $this->expense_vendor);
                })
                ->when(!empty($this->project), function ($query, $item) {
                    return $query->where('project_id', $this->project);
                })
                ->orderBy('date', 'desc')
                // ->get();
                // ->simplePaginate($paginate_number, ['*'], 'expenses_page');
                ->paginate($paginate_number, pageName: 'expenses-page');
        // dd($expenses);

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
        ]);
    }
}

