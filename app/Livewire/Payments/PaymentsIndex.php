<?php

namespace App\Livewire\Payments;

use App\Models\Project;
use App\Models\Payment;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentsIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    public Project $project;
    public $view = NULL;

    public $sortBy = 'date';
    public $sortDirection = 'desc';

    // public function mount(){
    //     dd($this->project);
    // }

    #[Computed]
    public function payments()
    {
        if(isset($this->project)){
            $payments = $this->project->payments()->paginate(10);
                // Payment::where('project_id', $this->project->id)->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
                // ->paginate(10);

            // dd($payments);
        }else{
            $payments =
                Payment::tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
                ->paginate(10);
        }

        return $payments;
    }

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Title('Payments')]
    public function render()
    {
        return view('livewire.payments.index');
    }
}
