<?php

namespace App\Livewire\Planner;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Livewire\Forms\TaskForm;

use App\Models\Project;
use App\Models\Task;
use App\Models\Vendor;

use Carbon\Carbon;
use Flux;

class PlannerCard extends Component
{
    public Project $project;
    public $task_date = NULL;
    public TaskForm $form;

    public $draft = '';

    //$projects comes from PlannerIndex component
    public $projects = [];
    public $vendors = [];
    public $employees = [];

    public $view_text = [
        'card_title' => 'Create Task',
        'button_text' => 'Create',
        'form_submit' => 'save',
    ];

    public function mount()
    {
        $this->form->project_id = $this->project->id;
        // $this->vendors = Vendor::whereNot('business_type', 'Retail')->get();
        // $this->employees = auth()->user()->vendor->users()->employed()->get();
    }

    public function form_modal()
    {
        $this->modal('task_create_form_modal')->show();
    }

    public function add()
    {
        $this->query()->create([
            'title' => $this->pull('draft'),
            'type' => 'Task',
            'duration' => 1,
        ]);
    }

    public function sort($key, $position)
    {
        $task = Task::findOrFail($key);

        // If this Task does not belong to this Project,
        if($task->project->isNot($this->project)) {
            $task->displace();
            $task->project()->associate($this->project);
        }

        $task->move($position);

        Flux::toast(
            duration: 1000,
            position: 'top right',
            variant: 'success',
            heading: 'Task Moved',
            // route / href / wire:click
            text: '',
        );
    }

    #[Computed]
    public function tasks()
    {
        $task_date = Carbon::parse($this->task_date);
        // return $this->query()->get();
        return $this->query()->get()->filter(function($item) use($task_date){
            if(is_null($item->start_date) && $this->task_date == NULL){
                return $item;
            }else{

            }
        });
    }

    // protected
    public function query()
    {
        return $this->project->tasks();
    }

    public function save()
    {
        $this->form->store();
        $this->form->reset();
        $this->form->project_id = $this->project->id;
        // $this->tasks;
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

    public function render()
    {
        return view('livewire.planner.card');
    }
}
