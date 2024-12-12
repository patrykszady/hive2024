<?php

namespace App\Livewire\Planner;

use App\Models\Project;
use App\Models\Task;
use App\Models\Vendor;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Flux;

class PlannerIndex extends Component
{
    public $employees = [];
    public $projects = [];
    public $vendors = [];
    public $days = [];
    public $week = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        if($this->week){
            //5-24-2024 must be Y-m-d format, else go to else below
            $monday = $this->week;
        }else{
            $monday = today()->format('Y-m-d');
        }

        $this->days = $this->set_week_days($monday);

        $this->projects = Project::with('tasks')
            ->status(['Active', 'Scheduled', 'Service Call', 'Invited'])
            ->sortBy([['last_status.title', 'asc'], ['last_status.start_date', 'desc']]);

        // 12-9-2024 also used in VendorIndex .. needs to be a global scope
        $this->vendors = Vendor::whereNot('business_type', 'Retail')
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

    public function set_week_days($monday)
    {
        $days = CarbonPeriod::create(
            Carbon::parse($monday)->startOfWeek(Carbon::MONDAY),
            '1 day',
            Carbon::parse($monday)->addWeek()->endOfWeek(Carbon::SUNDAY)
        );

        $week_days = [
            0 => [
                "database_date" => NULL,
                "formatted_date" => NULL,
                "is_today" => false,
                "is_weekend" => false,
                'is_saturday' => false,
                'is_sunday' => false
                ]
            ];

        foreach($days as $confirmed_date){
            //need to account for saturday&sunday / days off
            $week_days[] = [
                'database_date' => $confirmed_date->format('Y-m-d'),
                'formatted_date' => $confirmed_date->format('D, m/d'),
                'is_today' => $confirmed_date == today(),
                'is_weekend' => $confirmed_date->isWeekend(),
                'is_saturday' => $confirmed_date->isSaturday(),
                'is_sunday' => $confirmed_date->isSunday()
            ];
        }

        return $week_days;
    }

    public function sort($key, $position, $project_id, $date_index)
    {
        $date_database = $this->days[$date_index]['database_date'];
        $project = Project::findOrFail($project_id);
        $task = Task::findOrFail($key);

        // If this Task does not belong to this Project
        if($task->project->isNot($project)) {
            $task->displace();
            $task->project()->associate($project);
        }

        $task->start_date = $date_database;
        $task_days_count = $task->duration;

        if(in_array($task_days_count, [0, 1])){
            $task->end_date = $task->start_date;
            $task->duration = 1;
        }else{
            $task->end_date = Carbon::parse($task->start_date)->addDays($task_days_count - 1)->format('Y-m-d');
        }

        $task->save();
        $task->move($position);

        Flux::toast(
            duration: 2000,
            position: 'top right',
            variant: 'success',
            heading: 'Task Moved',
            // route / href / wire:click
            text: '',
        );
    }

    //2024-12-09 Copilot help
    //2024-12-10 SAME ON Tasks/TaskCreate
    //count days between dates and ignore weekend days if between


    // #[Computed]
    // public function projects()
    // {
    //     return Project::with('tasks')
    //         ->status(['Active', 'Scheduled', 'Service Call', 'Invited'])
    //         ->sortBy([['last_status.title', 'asc'], ['last_status.start_date', 'desc']]);
    // }

    #[Title('Planner')]
    public function render()
    {
        return view('livewire.planner.index');
    }
}
