<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

use Illuminate\Support\Facades\DB;
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

    public function remove($task_id)
    {
        $task = $this->query()->findOrFail($task_id);

        //999999 = $position. CHANGE!!!
        $this->move($task, 999999);

        $task->delete();
    }

    public function sort($key, $position)
    {
        $task = $this->query()->findOrFail($key);

        $this->move($task, $position);
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

    protected function move($task, $position)
    {
        DB::transaction(function() use($task, $position){
            $current = $task->order;
            $after = $position;

            //If there was no position change, dont shift
            if($current === $after) return;

            // move the target todo out of the position stack
            $task->update(['order' => -1]);

            //Grab the shifted block and shift it up or down
            $block = $this->query()->whereBetween('order', [
                    min($current, $after),
                    max($current, $after),
                ]);

            $needToShiftBlockBecauseDraggingTargetDown = $current < $after;

            $needToShiftBlockBecauseDraggingTargetDown
                ? $block->decrement('order')
                : $block->increment('order');

            //place target back in position stack
            $task->update(['order' => $after]);
        });
    }

    //render method not needed if view and component follow a convention
    // public function render()
    // {
    //     return view('livewire.tasks.planner-list');
    // }
}
