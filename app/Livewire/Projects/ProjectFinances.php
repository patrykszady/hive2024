<?php

namespace App\Livewire\Projects;

use App\Models\Project;

use Livewire\Component;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectFinances extends Component
{
    use AuthorizesRequests;

    public Project $project;
    public $finances = [];

    public function mount()
    {
        $this->finances = $this->project->finances;
    }

    public function render()
    {
        return view('livewire.projects.project-finances');
    }
}
