<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;

use Livewire\Component;
use Livewire\Attributes\Computed;

class PlannerCard extends Component
{
    public Project $project;
    public $task_date = NULL;

    protected $listeners = ['refreshComponent' => '$refresh', 'refresh_planner'];
    // public $draft = '';

    // public function add()
    // {
    //     $this->project->tasks()->create([
    //         'title' => $this->pull('draft'),
    //         'duration' => 0,
    //         'type' => 'Task',
    //     ]);
    // }

    // public function remove($task_id)
    // {
    //     $task = $this->query()->findOrFail($task_id)->delete();
    // }

    public function sort($key, $position)
    {
        // dd($key, $position, $this->project);
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

    public function refresh_planner(Task $task, $task_date)
    {
        // dd($task);
        // unset($this->tasks);
        // $task = Task::where('belongs_to_vendor_id', auth()->user()->vendor->id)->findOrFail($task_id);
        // $task->move(1);
        // $this->tasks();
        // $this->project = $task->project;
        // $this->task_date = $task_date;
        // $this->query();
        // dd($this->project);
        // dd($task_id, $task_date);

        // $this->sort($task_id, 0);
    }

    //render method not needed if view and component follow a convention
    public function render()
    {
        return view('livewire.tasks.planner-card');
    }
}

