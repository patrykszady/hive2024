<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class PlannerList extends Component
{
    // public Project $project;

    // public $projects = [];
    public $draft = '';

    // public function mount()
    // {
    //     $this->tasks = Task::where('project_id', 241)->get();
    //     // $this->projects = Project::with('tasks')->status(['Active']);
    // }

    public function add()
    {
        Project::findOrFail(241)->tasks()->create([
            'title' => $this->pull('draft'),
            'duration' => 0,
            'order' => $this->query()->max('order') + 1,
            'type' => 'Task',
        ]);
    }

    #[Computed]
    public function tasks()
    {
        //orderBy 'order'
        return $this->query()->orderBy('order')->get();
    }

    protected function query()
    {
        return Task::where('project_id', 241);
    }

    //render method not needed if view and component follow a convention
    // public function render()
    // {
    //     return view('livewire.tasks.planner-list');
    // }
}
