<?php

namespace App\Livewire\Tasks;

use App\Models\Task;

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
    //$projects & $vendors & $employees come from the Planner Component
    public $projects = [];
    public $vendors = [];
    public $employees = [];

    public $view_text = [
        'card_title' => 'Create Task',
        'button_text' => 'Create',
        'form_submit' => 'save',
    ];

    protected $listeners = ['editTask', 'addTask'];

    public function updated($field, $value)
    {
        if($field === 'form.start_date' && is_null($this->form->end_date) OR $this->form->end_date <= $value){
            $this->form->end_date = $value;
            $this->form->duration = 1;
        // }elseif($field === 'form.start_date' || 'form.end_date'){
        //     // $start = $this->form->start_date;
        //     // $end = $this->form->end_date;
        //     $start = Carbon::parse($this->form->start_date);
        //     $end = Carbon::parse($this->form->end_date);
        // }



        // }else{
        //     // $start = $this->form->start_date;
        //     // $end = $this->form->end_date;
        //     $start = Carbon::parse($this->form->start_date);
        //     $end = Carbon::parse($this->form->end_date);

        //     //if weekend .. add/subtract days
        //     // if(!empty($this->form->include_weekend_days)){
        //     //     $saturday = isset($this->form->include_weekend_days->saturday) ? $this->form->include_weekend_days->saturday : false;
        //     //     $sunday = isset($this->form->include_weekend_days->sunday) ? $this->form->include_weekend_days->sunday : false;

        //     //     $duration = $this->countDaysBetweenDates($start, $end, $saturday, $sunday) + 1;
        //     // }else{
        //     //     $duration = $end->diff($start)->days;
        //     // }
        //     dd($this->form->include_weekend_days->saturday, $this->form->include_weekend_days->saturday);

        //     $saturday = isset($this->form->include_weekend_days->saturday) ? $this->form->include_weekend_days->saturday : false;
        //     $sunday = isset($this->form->include_weekend_days->sunday) ? $this->form->include_weekend_days->sunday : false;
        //     // dd($saturday, $sunday);
        //     $duration = $this->countDaysBetweenDates($start, $end, $saturday, $sunday) + 1;

        //     $this->form->duration = $duration;
        // }
        }
        $this->validateOnly($field);
    }

    //2024-12-09 Copilot help
    //2024-12-10 SAME ON PlannerIndex
    //count days between dates and ignore weekend days if between
    function countDaysBetweenDates($startDate, $endDate, $excludeWeekends = false, $excludeSaturdays = false, $excludeSundays = false) {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $period = CarbonPeriod::create($start, $end);

        $daysCount = 0;

        foreach ($period as $date) {
            $isSaturday = $date->isSaturday();
            $isSunday = $date->isSunday();

            if ($excludeWeekends && ($isSaturday || $isSunday)) {
                continue;
            }

            if ($excludeSaturdays && $isSaturday) {
                continue;
            }

            if ($excludeSundays && $isSunday) {
                continue;
            }

            $daysCount++;
        }

        return $daysCount;
    }

    // Example usage:
    // $startDate = '2024-01-01';
    // $endDate = '2024-01-10';
    // $daysCount = countDaysBetweenDates($startDate, $endDate, true, false, true);
    // echo "Total days: $daysCount";

    // function countDaysBetweenDates($start, $end, $includeSaturday, $includeSunday) {
    //     // dd($start, $end);
    //     // $start = Carbon::parse($startDate);
    //     // $end = Carbon::parse($endDate);

    //     $days = $start->diffInDaysFiltered(function (Carbon $date) use ($includeSaturday, $includeSunday) {
    //         if (!$includeSaturday && $date->isSaturday()) {
    //             return false;
    //         }
    //         if (!$includeSunday && $date->isSunday()) {
    //             return false;
    //         }
    //         return true;
    //     }, $end);

    //     if($days === 0){
    //         $days = 1;
    //     }

    //     return $days;
    // }

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

        $this->dispatch('refreshComponent')->to(PlannerIndex::class);
        $this->modal('task_create_form_modal')->close();

        Flux::toast(
            duration: 3000,
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
            duration: 3000,
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
            duration: 3000,
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
