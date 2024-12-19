<?php

namespace App\Livewire\Estimates;

use App\Models\Estimate;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;

use Flux;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EstimatesIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    public $view = 'estimates.index';
    public $project = NULL;

    #[Computed]
    public function estimates()
    {
        $project_id = $this->project ? $this->project->id : NULL;
        return Estimate::withTrashed()->orderBy('created_at', 'DESC')
            ->when($this->project != NULL, function ($query) use ($project_id) {
                return $query->where('project_id', $project_id);
            })
            ->paginate(10);
    }

    public function deleteEstimate(Estimate $estimate)
    {
        $this->estimate = $estimate;
        $estimate->delete();

        Flux::toast(
            duration: 10000,
            position: 'top right',
            variant: 'success',
            heading: 'Estimate Removed',
            // route / href / wire:click
            text: '',
        );
        // $this->redirectRoute('projects.show', ['project' => $estimate->project->id]);
    }

    #[Title('Estimates')]
    public function render()
    {
        // $this->authorize('viewAny', Project::class);
        return view('livewire.estimates.index');
    }
}
