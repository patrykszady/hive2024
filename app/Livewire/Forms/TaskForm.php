<?php

namespace App\Livewire\Forms;

use App\Models\Task;

use Livewire\Attributes\Validate;
use Livewire\Form;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Carbon\Carbon;

class TaskForm extends Form
{
    use AuthorizesRequests;

    #[Validate('required')]
    public $title = NULL;

    #[Validate('array')]
    public $dates = NULL;

    #[Validate('required')]
    public $project_id = NULL;

    #[Validate('nullable')]
    public $duration = 0;

    #[Validate('nullable')]
    public $order = NULL;

    #[Validate('nullable')]
    public $vendor_id = NULL;

    #[Validate('nullable')]
    public $user_id = NULL;

    #[Validate('required')]
    public $type = 'Task';

    #[Validate('nullable')]
    public $notes = NULL;

    public ?Task $task;

    public function setTask(Task $task)
    {
        $this->task = $task;
        // if(!isset($task->start_date)){
        //     $new_dates = [];
        // }elseif($task->start_date === $task->end_date OR is_null($task->end_date)){
        //     $new_dates = [$task->start_date->format('m/d/Y')];
        // }else{
        //     $new_dates = [$task->start_date->format('m/d/Y'), $task->end_date->format('m/d/Y')];
        // }
        if(!isset($task->start_date)){
            $new_dates = [];
        }else{
            $new_dates = [$task->start_date->format('m/d/Y'), $task->start_date->format('m/d/Y')];
        }

        $this->dates = $new_dates;
        $this->project_id = $task->project_id;
        $this->order = $task->order;
        $this->duration = $task->duration;
        $this->vendor_id = $task->vendor_id;
        $this->type = $task->type;
        $this->title = $task->title;
        $this->notes = $task->notes;
        $this->user_id = $task->user_id;
    }

    public function update()
    {
        $this->authorize('update', $this->task);
        $this->validate();
        $task = $this->task->update([
            // 'start_date' => isset($this->dates[0]) ? (!empty($this->dates[0]) ? $this->dates[0] : NULL) : NULL,
            // 'end_date' => isset($this->dates[1]) ? $this->dates[1] : (isset($this->dates[0]) ? (!empty($this->dates[0]) ? $this->dates[0] : NULL) : NULL),
            'start_date' => isset($this->dates[0]) ? (!empty($this->dates[0]) ? $this->dates[0] : NULL) : NULL,
            'end_date' => isset($this->dates[0]) ? (!empty($this->dates[0]) ? $this->dates[0] : NULL) : NULL,
            'project_id' => $this->project_id,
            'vendor_id' => $this->vendor_id,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'notes' => $this->notes,
            'duration' => $this->duration,
            'order' => $this->order
        ]);
    }

    public function store()
    {
        // $this->authorize('create', Expense::class);
        $this->validate();
        $task = Task::create([
            'start_date' => isset($this->dates[0]) ? (!empty($this->dates[0]) ? $this->dates[0] : NULL) : NULL,
            'end_date' => isset($this->dates[0]) ? (!empty($this->dates[0]) ? $this->dates[0] : NULL) : NULL,
            'project_id' => $this->project_id,
            'vendor_id' => $this->vendor_id,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'notes' => $this->notes,
            'order' => 1,
            'duration' => $this->duration
        ]);
    }
}
