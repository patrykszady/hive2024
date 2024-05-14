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

    #[Validate('required')]
    public $duration = 1;

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
        if($task->start_date === $task->end_date){
            $new_dates = [$task->start_date->format('m/d/Y')];
        }else{
            $new_dates = [$task->start_date->format('m/d/Y'), $task->end_date->format('m/d/Y')];
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
        // $this->authorize('create', Expense::class);
        $this->validate();
        $task = $this->task->update([
            'start_date' => $this->dates[0],
            'end_date' => isset($this->dates[1]) ? $this->dates[1] : $this->dates[0],
            'project_id' => $this->project_id,
            'vendor_id' => $this->vendor_id,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'notes' => $this->notes,
            'duration' => $this->duration,
            'order' => $this->order,
            'belongs_to_vendor_id' => auth()->user()->vendor->id,
            'created_by_user_id' => auth()->user()->id,
        ]);
    }

    public function store()
    {
        // $this->authorize('create', Expense::class);
        $this->validate();
        $task = Task::create([
            'start_date' => $this->dates[0],
            'end_date' => isset($this->dates[1]) ? $this->dates[1] : $this->dates[0],
            'project_id' => $this->project_id,
            'vendor_id' => $this->vendor_id,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'notes' => $this->notes,
            'order' => 1,
            'duration' => $this->duration,
            'belongs_to_vendor_id' => auth()->user()->vendor->id,
            'created_by_user_id' => auth()->user()->id,
        ]);
    }
}
