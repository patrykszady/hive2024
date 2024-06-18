<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;
use App\Models\Vendor;

use Livewire\Component;
use App\Livewire\Forms\TaskForm;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Carbon\Carbon;

class TaskCreate extends Component
{
    use AuthorizesRequests;

    public TaskForm $form;
    //$projects come from the Planner Component
    public $projects = [];
    public $vendors = [];
    public $employees = [];

    public $view_text = [
        'card_title' => 'Create Task',
        'button_text' => 'Create',
        'form_submit' => 'save',
    ];

    public $showModal = FALSE;

    protected $listeners = ['editTask', 'addTask'];

    public function mount()
    {
        $this->vendors = Vendor::whereNot('business_type', 'Retail')->get();
        $this->employees = auth()->user()->vendor->users()->employed()->get();
    }

    public function addTask($project_id, $date = NULL)
    {
        $this->form->reset();

        $this->view_text = [
            'card_title' => 'Create Task',
            'button_text' => 'Create',
            'form_submit' => 'save',
        ];

        if($date){
            $this->form->dates = [Carbon::parse($date)->format('m/d/Y')];
        }else{
            $this->form->dates = [];
        }

        $this->form->project_id = $project_id;
        $this->showModal = TRUE;
    }

    // 5-7-2024 for flatpickr only... anyay to optimize?
    public function dateschanged($dates)
    {
        $this->form->dates = $dates;

        if(count($dates) > 1){
            $start = Carbon::parse($dates[0]);
            $end = Carbon::parse($dates[1]);

            $duration = $end->diff($start)->days + 1;

            $this->form->duration = $duration;
        }elseif(empty($dates[0])){
            $this->form->duration = 0;
        }else{    
            $this->form->duration = 1;
        }
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

    public function removeTask()
    {
        $task = $this->form->task;

        $task->delete();

        $this->dispatch('notify',
            type: 'success',
            content: 'Task Removed'
        );

        $this->dispatch('refresh_planner')->to(PlannerProject::class);
        $this->showModal = FALSE;
    }

    public function save()
    {
        $this->form->store();

        $this->dispatch('notify',
            type: 'success',
            content: 'Task Created'
        );

        $this->dispatch('refresh_planner')->to(PlannerProject::class);
        $this->showModal = FALSE;
    }

    public function edit()
    {
        $this->form->update();

        $this->dispatch('notify',
            type: 'success',
            content: 'Task Updated'
        );

        $this->dispatch('refresh_planner')->to(PlannerProject::class);
        $this->showModal = FALSE;
    }

    public function render()
    {
        return view('livewire.tasks.create');
    }
}
