<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;

use Livewire\Component;
use Livewire\Attributes\Computed;

class PlannerCard extends Component
{
    public Project $project;
    public $task_date = '';

    // public $draft = '';

    // public function add()
    // {
    //     $this->project->tasks()->create([
    //         'title' => $this->pull('draft'),
    //         'duration' => 0,
    //         'type' => 'Task',
    //     ]);
    // }

    public function remove($task_id)
    {
        $task = $this->query()->findOrFail($task_id)->delete();
    }

    public function sort($key, $position)
    {
        $task = Task::where('belongs_to_vendor_id', auth()->user()->vendor->id)->findOrFail($key);

        //If this task does not belong to this project
        if($task->project->isNot($this->project)){
            $task->displace();

            //transfer ownership of task
            $task->project()->associate($this->project);
        }

        $task->start_date = $this->task_date;
        $task->end_date = $this->task_date;
        $task->save();

        //finish moving task to another project
        $task->move($position);
    }

    #[Computed]
    public function tasks()
    {
        return $this->query()->get();
    }

    protected function query()
    {
        return $this->project->tasks()->where('start_date', $this->task_date);
    }

    //render method not needed if view and component follow a convention
    // public function render()
    // {
    //     return view('livewire.tasks.planner-list');
    // }
}

