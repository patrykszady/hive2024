<?php

namespace App\Livewire\Leads;

use App\Models\Lead;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LeadsIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    public $origin = '';
    public $view = NULL;
    public $sortBy = 'date';
    public $sortDirection = 'desc';

    protected $queryString = [
        'origin' => ['except' => '']
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    #[Computed]
    public function leads()
    {
        $leads = Lead::with(['user', 'last_status'])->when($this->origin, function ($query) {
                return $query->where('origin', $this->origin);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        return $leads;
    }

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Title('Leads')]
    public function render()
    {
        return view('livewire.leads.index');
    }
}
