<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;

use Livewire\Component;
use Livewire\Attributes\Computed;

use Carbon\Carbon;

class PlannerCard extends Component
{
    public Project $project;
    public $task_date = NULL;

    protected $listeners = ['refreshComponent' => '$refresh'];
    // public $draft = '';

    // public function add()
    // {
    //     $this->project->tasks()->create([
    //         'title' => $this->pull('draft'),
    //         'duration' => 1,
    //         'start_date' => '2024-09-16',
    //         'end_date' => '2024-09-16',
    //         'type' => 'Task',
    //         'order' => 0,
    //     ]);
    // }

    // public function addnew(Project $project)
    // {
    //     $this->project = $project;

    //     Task::create([
    //             // 'title' => $this->pull('draft'),
    //             'project_id' => $project->id,
    //             'title' => 'test',
    //             'duration' => 1,
    //             'start_date' => '2024-09-16',
    //             'end_date' => '2024-09-16',
    //             'type' => 'Task',
    //             'order' => 0
    //         ]);
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
        $task_days_count = $task->duration;

        if(in_array($task_days_count, [0, 1])){
            $task->end_date = $task->start_date;
            $task->duration = 1;
        }else{
            $task->end_date = Carbon::parse($task->start_date)->addDays($task_days_count - 1)->format('Y-m-d');
        }

        $task->save();
        //finish moving task to another project
        $task->move($position);

        $this->render();
    }

    #[Computed]
    public function tasks()
    {
        $task_date = Carbon::parse($this->task_date);
        //where $this->task_date is between start_date and end_date on this task
        // return $this->query()->whereDate('start_date', '>=', $this->task_date)->whereDate('end_date', '<=', $this->task_date)->get();
        return $this->query()->get()->filter(function($item) use($task_date){
            if(is_null($item->start_date) && $this->task_date == NULL){
                return $item;
            }elseif($task_date->between($item->start_date, $item->end_date) && $this->task_date != NULL) {
                // dd(is_null($item->options->));
                // dd($item->start_date->isSaturday());
                if(isset($item->options['include_weekend_days'])){
                    if($task_date->isSaturday() && $item->options['include_weekend_days']['saturday'] == true){
                        return $item;
                    }elseif($task_date->isSaturday() && $item->options['include_weekend_days']['saturday'] == false){

                    }else{
                        if($task_date->isSunday() && $item->options['include_weekend_days']['sunday'] == true){
                            return $item;
                        }elseif($task_date->isSunday() && $item->options['include_weekend_days']['sunday'] == false){

                        }else{
                            return $item;
                        }
                    }
                    // dd($item->options['include_weekend_days']);
                }else{
                    return $item;
                }
            }
        });
    }

    protected function query()
    {
        return $this->project->tasks();
    }

    //render method not needed if view and component follow a convention
    public function render()
    {
        return view('livewire.tasks.planner-card');
    }
}

