<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;
use App\Models\Vendor;

use Livewire\Component;
use App\Livewire\Forms\TaskForm;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskCreate extends Component
{
    use AuthorizesRequests;

    public TaskForm $form;
    //$projects come from the Planner component
    public $projects = [];
    public $vendors = [];
    public $employees = [];

    public $view_text = [
        'card_title' => 'Create Task',
        'button_text' => 'Create',
        'form_submit' => 'save',
    ];

    public $showModal = FALSE;

    //'refreshComponent' => '$refresh',
    protected $listeners = ['editTask', 'addTask'];

    public function mount()
    {
        $this->vendors = Vendor::whereNot('business_type', 'Retail')->get();
        $this->employees = auth()->user()->vendor->users()->employed()->get();
    }

    public function addTask($project_id)
    {
        $this->form->reset();

        $this->view_text = [
            'card_title' => 'Create Task',
            'button_text' => 'Create',
            'form_submit' => 'save',
        ];

        $this->form->start_date = today()->format('Y-m-d');
        $this->form->project_id = $project_id;
        $this->showModal = TRUE;
    }

    public function editTask(Task $task)
    {
        $this->view_text = [
            'card_title' => 'Update Task',
            'button_text' => 'Update',
            'form_submit' => 'edit',
        ];

        $this->form->setTask($task);
        $this->showModal = TRUE;
    }

    public function save()
    {
        $task = $this->form->store();

        $this->dispatch('notify',
            type: 'success',
            content: 'Task Created'
        );

        $this->dispatch('refresh')->to(Planner::class);
        // $this->dispatch('refreshParentComponent');
        // $this->dispatch('refresh')->to('tasks.planner');

        $this->showModal = FALSE;
    }

    public function edit()
    {
        $task = $this->form->update();

        $this->dispatch('notify',
            type: 'success',
            content: 'Task Updated'
        );

        $this->dispatch('refresh')->to(Planner::class);
        // $this->dispatch('refreshParentComponent');
        // $this->dispatch('refresh')->to('tasks.planner');

        $this->showModal = FALSE;
    }

    public function render()
    {
        return view('livewire.tasks.create');
    }
}
