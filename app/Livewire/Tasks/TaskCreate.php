<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;
use App\Models\Vendor;

use Livewire\Component;
use App\Livewire\Forms\TaskForm;
use App\Livewire\Planner\PlannerIndex;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Flux;
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

    protected $listeners = ['editTask', 'addTask'];

    public function mount()
    {
        // $this->form->dates[0] = today()->format('m/d/Y');
        $this->vendors = Vendor::whereNot('business_type', 'Retail')
            // 12-9-2024 also used in VendorIndex .. needs to be a global scope
            ->withCount([
                'expenses',
                'expenses as expense_count' => function ($query) {
                    $query->where('created_at', '>=', today()->subYear());
                }
            ])
            //as expense count
            // sort by expenses ytd
            ->tap(fn ($query) => 'expense_count' ? $query->orderBy('expense_count', 'desc') : $query)
            ->get();
        $this->employees = auth()->user()->vendor->users()->employed()->get();
    }

    public function updated($field, $value)
    {
        if($field === 'form.start_date' && is_null($this->form->end_date)){
            $this->form->end_date = $value;
            $this->form->duration = 1;
        }

        if($field === 'form.start_date' || 'form.end_date'){
            $start = Carbon::parse($this->form->start_date);
            $end = Carbon::parse($this->form->end_date);

            $duration = $end->diff($start)->days + 1;

            $this->form->duration = $duration;
        }
    }

    public function addTask($project_id, $date = NULL)
    {
        $this->form->reset();
        $this->resetErrorBag();

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
        $this->modal('task_create_form_modal')->show();
    }

    public function editTask(Task $task)
    {
        $this->resetErrorBag();

        $this->view_text = [
            'card_title' => 'Edit Task',
            'button_text' => 'Update',
            'form_submit' => 'edit',
        ];

        $this->form->setTask($task);
        $this->modal('task_create_form_modal')->show();
    }

    public function removeTask()
    {
        $task = $this->form->task;
        $task->delete();

        $this->dispatch('render')->to(PlannerCard::class);
        // $this->dispatch('refresh_planner')->to(PlannerList::class);
        $this->modal('task_create_form_modal')->close();

        Flux::toast(
            duration: 5000,
            position: 'top right',
            variant: 'success',
            heading: 'Task Removed',
            // route / href / wire:click
            text: '',
        );
    }

    // 5-7-2024 for flatpickr only... anyway to optimize?
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

    public function save()
    {
        $this->form->store();
        $this->dispatch('refreshComponent')->to(PlannerIndex::class);
        $this->modal('task_create_form_modal')->close();

        Flux::toast(
            duration: 2000,
            position: 'top right',
            variant: 'success',
            heading: 'Task Created',
            // route / href / wire:click
            text: '',
        );
    }

    public function edit()
    {
        $this->authorize('update', $this->form->task);
        $task = $this->form->update();

        $this->dispatch('refreshComponent')->to(PlannerIndex::class);

        $this->modal('task_create_form_modal')->close();

        Flux::toast(
            duration: 2000,
            position: 'top right',
            variant: 'success',
            heading: 'Task Updated',
            // route / href / wire:click
            text: '',
        );
    }

    public function render()
    {
        return view('livewire.tasks.create');
    }
}
