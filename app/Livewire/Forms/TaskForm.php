<?php

namespace App\Livewire\Forms;

use App\Models\Task;

use Livewire\Attributes\Validate;
use Livewire\Form;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskForm extends Form
{
    use AuthorizesRequests;

    #[Validate('required')]
    public $title = NULL;

    #[Validate('required|date|after:2017-01-01')]
    public $start_date = NULL;

    #[Validate('required')]
    public $project_id = NULL;

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

        $this->start_date = $task->start_date;
        $this->project_id = $task->project_id;
        $this->vendor_id = $task->vendor_id;
        $this->type = $task->type;
        $this->title = $task->title;
        $this->notes = $task->notes;
        $this->user_id = $task->user_id;
    }

    // public function create_title()
    // {
    //     if($this->title){
    //         $title = $this->title;
    //     }elseif($this->vendor_id){
    //         $title = $this->component->vendors->find($this->vendor_id)->name;
    //     }elseif($this->user_id){
    //         $title = $this->component->employees->find($this->user_id)->first_name;
    //     }

    //     return $title;
    // }

    public function update()
    {
        // $this->authorize('create', Expense::class);
        $this->validate();
        $task = $this->task->update([
            'start_date' => $this->start_date,
            'project_id' => $this->project_id,
            'vendor_id' => $this->vendor_id,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'notes' => $this->notes,
            'position' => 1,
            'duration' => 1,
            'belongs_to_vendor_id' => auth()->user()->vendor->id,
            'created_by_user_id' => auth()->user()->id,
        ]);

        return $task;
    }

    public function store()
    {
        // $this->authorize('create', Expense::class);
        $this->validate();
        $task = Task::create([
            'start_date' => $this->start_date,
            'project_id' => $this->project_id,
            'vendor_id' => $this->vendor_id,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'notes' => $this->notes,
            'position' => 1,
            'duration' => 1,
            'belongs_to_vendor_id' => auth()->user()->vendor->id,
            'created_by_user_id' => auth()->user()->id,
        ]);

        return $task;
    }
}
