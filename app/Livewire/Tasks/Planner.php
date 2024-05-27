<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;

use Livewire\Component;
use Livewire\Attributes\Title;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class Planner extends Component
{
    public $days = [];
    public $projects = [];
    public $tasks = [];
    public $week = '';

    protected $listeners = ['refresh_test'];

    protected $queryString = [
        'week' => ['except' => ''],
    ];

    public function mount()
    {
        if($this->week){
            //5-24-2026 must be Y-m-d format, else go to else below
            $monday = $this->week;
        }else{
            $monday = today()->format('Y-m-d');
        }

        $this->set_week_days($monday);

        //tasks where between week
        $this->projects =
            Project::with(['tasks' => function($query) {
                $query->whereBetween('start_date', [$this->days[0]['database_date'], $this->days[6]['database_date']])->orWhereBetween('end_date', [$this->days[0]['database_date'], $this->days[6]['database_date']]);
            }])
            ->status(['Active', 'Scheduled', 'Service Call'])->sortByDesc('last_status.start_date')->values();

        $project_ids = $this->projects->pluck('id');

        $tasks = 
            Task::whereIn('project_id', $project_ids)
                ->whereBetween('start_date', [$this->days[0]['database_date'], $this->days[6]['database_date']])
                ->orWhereBetween('end_date', [$this->days[0]['database_date'], $this->days[6]['database_date']])
                ->get()
                ->each(function ($task) {
                    if($task->start_date->between($this->days[0]['database_date'], $this->days[6]['database_date']) && $task->end_date->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
                        $task->date = $task->start_date->format('Y-m-d');
                        $task->direction = 'left';
                    }elseif($task->start_date->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
                        $task->date = $task->start_date->format('Y-m-d');
                        $task->direction = 'left';
                    }elseif($task->end_date->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
                        $task->date = $task->end_date->format('Y-m-d');
                        $task->direction = 'right';
                    }
                })->groupBy('project_id');

        $no_date_tasks = Task::whereIn('project_id', $project_ids)->whereNull('start_date')->get()->groupBy('project_id');

        // Combine projects and tasks
        foreach($this->projects as $project_index => $project) {
            $this->projects[$project_index]->tasks = $tasks[$project->id] ?? collect();
            $this->projects[$project_index]->no_date_tasks = $no_date_tasks[$project->id] ?? collect();
        }
    }

    public function set_week_days($monday)
    {
        $days = new \DatePeriod(
            Carbon::parse($monday)->startOfWeek(Carbon::MONDAY),
            CarbonInterval::day(),
            Carbon::parse($monday)->startOfWeek(Carbon::MONDAY)->endOfWeek(Carbon::SUNDAY)
        );

        $this->days = [];
        foreach($days as $confirmed_date){
            //need to account for saturday&sunday / days off
            $this->days[] = [
                'database_date' => $confirmed_date->format('Y-m-d'),
                'formatted_date' => $confirmed_date->format('D, m/d')
            ];
        }
    }

    public function taskMoved($items)
    {
        foreach($items as $item){
            $task = Task::findOrFail($item['task_id']);

            if(is_null($task->start_date)){
                $days = collect($this->days);
                $task->start_date = $days[$item['x']]['database_date'];
                $task->end_date = $days[$item['x']]['database_date'];
                $task->save();
            }

            if($task->start_date->between($this->days[0]['database_date'], $this->days[6]['database_date']) && $task->end_date->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
                $task->date = $task->start_date;
                $task->direction = 'left';
            }elseif($task->start_date->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
                $task->date = $task->start_date;
                $task->direction = 'left';
            }elseif($task->end_date->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
                $task->date = $task->end_date;
                $task->direction = 'right';
            }

            $days = collect($this->days);
            $day_index = $days->where('database_date', $task->date->format('Y-m-d'))->keys()->first();

            if($task->direction == 'left' && 7 - $day_index < $task->duration){
                $duration = ($day_index - $item['x'] ) + $task->duration;
            }elseif($task->direction == 'right'){
                $duration = $task->duration + $item['w'] - ($day_index + 1);
            }else{
                $duration = $item['w'];
            }

            $task->duration = $duration;
            $task->order = $item['y'];

            if($task->direction == 'left'){
                $task->start_date = $this->days[$item['x']]['database_date'];
                $task->end_date = Carbon::parse($this->days[$item['x']]['database_date'])->addDays($duration - 1)->format('Y-m-d');
            }else{
                $task->end_date = $task->start_date->addDays($duration - 1)->format('Y-m-d');
            }

            unset($task->date);
            unset($task->direction);
            $task->save();
        }

        $this->mount();

        $this->dispatch('notify',
            type: 'success',
            content: 'Task Moved'
        );
    }

    public function refresh_test()
    {
        $this->mount();
    }

    public function weekToggle($direction)
    {
        $current_monday = $this->days[0]['database_date'];

        if($direction == 'next'){
            $monday = Carbon::parse($current_monday)->addWeek()->format('Y-m-d');
        }elseif($direction == 'previous'){
            $monday = Carbon::parse($current_monday)->subWeek()->format('Y-m-d');
        }

        $this->week = $monday;
        $this->mount();
    }

    #[Title('Planner')]
    public function render()
    {
        return view('livewire.tasks.planner');
    }
}
