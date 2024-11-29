<?php

namespace App\Livewire\Projects;

use App\Models\Project;

use Livewire\Component;
use Livewire\Attributes\Title;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Spatie\Browsershot\Browsershot;

class ProjectShow extends Component
{
    use AuthorizesRequests;

    public Project $project;

    protected $listeners = ['refreshComponent' => '$refresh'];

    //11/07/2024 took reimbursments to ProjectFiances

    #[Title('Project')]
    public function render()
    {
        // $this->authorize('view', $this->project);

        return view('livewire.projects.show');
    }
}
