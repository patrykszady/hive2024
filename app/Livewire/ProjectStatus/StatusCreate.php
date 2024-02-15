<?php

namespace App\Livewire\ProjectStatus;

use App\Models\Project;
use App\Models\ProjectStatus;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StatusCreate extends Component
{
    // use AuthorizesRequests;

    public Project $project;
    public $statuses = [];
    public $project_status = NULL;
    public $project_status_date = NULL;

    public function rules()
    {
        return [
            'project_status' => 'required',
        ];
    }

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->project_status_date = today()->format('Y-m-d');
        $this->statuses = $this->project->statuses()->orderBy('start_date', 'ASC')->get();
    }

    public function update_project()
    {
        $this->validate();
        $status =
            ProjectStatus::create([
                'project_id' => $this->project->id,
                'belongs_to_vendor_id' => auth()->user()->vendor->id,
                'title' => $this->project_status,
                'start_date' => $this->project_status_date
            ]);

        $this->project_status = NULL;
        $this->mount($this->project);
        $this->render();

        $this->dispatch('refreshComponent')->to('projects.project-show');

        $this->dispatch('notify',
            type: 'success',
            content: 'Status Updated'
        );
    }

    public function render()
    {
        return view('livewire.project-status.create');
    }
}
