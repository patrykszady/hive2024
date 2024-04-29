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
    public $week = '';

    protected $listeners = ['refresh_test'];

    protected $queryString = [
        'week' => ['except' => ''],
    ];

    public function mount()
    {
        if($this->week){
            //must be Y-m-d format, else go to else below
            $monday = $this->week;
        }else{
            $monday = today()->format('Y-m-d');
        }

        $this->set_week_days($monday);

        // //tasks where between week
        // $this->projects =
        //     Project::with(['tasks' => function($query) {
        //         $query->whereBetween('start_date', [$this->days[0]['database_date'], $this->days[6]['database_date']])->orWhereBetween('end_date', [$this->days[0]['database_date'], $this->days[6]['database_date']]);
        //         // ->lazy()
        //         // ->each(function($task, $key) {
        //         //     $task->test = 'test';
        //         //     $task->setAttribute('date', $task->end_date);
        //         //     // if(Carbon::parse($task->start_date)->between($this->days[0]['database_date'], $this->days[6]['database_date']) && Carbon::parse($task->end_date)->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
        //         //     //     $task->setAttribute('date', $task->start_date);
        //         //     // }elseif(Carbon::parse($task->start_date)->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
        //         //     //     $task->setAttribute('date', $task->start_date);
        //         //     // }elseif(Carbon::parse($task->end_date)->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
        //         //     //     $task->setAttribute('date', $task->end_date);
        //         //     // }

        //         //     dd($task);
        //         // });
        //     }])
        //     ->status(['Active', 'Scheduled'])->sortByDesc('last_status.start_date');
        // // dd($this->projects->first()->tasks->first());



        //tasks where between week,,,
        $this->projects =
            Project::with(['tasks' => function($query) {
                $query->whereBetween('start_date', [$this->days[0]['database_date'], $this->days[6]['database_date']])->orWhereBetween('end_date', [$this->days[0]['database_date'], $this->days[6]['database_date']]);
            }])
            ->status(['Active', 'Scheduled'])->sortByDesc('last_status.start_date')->values();

        $project_ids = $this->projects->pluck('id');

        $tasks = Task::whereIn('project_id', $project_ids)->get()->each(function ($task) {
            if(Carbon::parse($task->start_date)->between($this->days[0]['database_date'], $this->days[6]['database_date']) && Carbon::parse($task->end_date)->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
                $task->date = $task->start_date;
                $task->direction = 'left';
            }elseif(Carbon::parse($task->start_date)->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
                $task->date = $task->start_date;
                $task->direction = 'left';
            }elseif(Carbon::parse($task->end_date)->between($this->days[0]['database_date'], $this->days[6]['database_date'])){
                $task->date = $task->end_date;
                $task->direction = 'right';
            }
        })->groupBy('project_id');

        // Combine projects and tasks
        foreach($this->projects as $project_index => $project) {
            $this->projects[$project_index]->tasks = $tasks[$project->id] ?? collect();
        }

        // dd($this->projects->first()->tasks);
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
            // dd([$task, $item, $this->days[$item['x']]['database_date']]);

            //if $this->days[$item['x']]['database_date'] IS BEFORE $item->start_date, add the diffInDays to $task->duration
            // $item_day = Carbon::parse($this->days[$item['x']]['database_date']);
            // $task_day = Carbon::parse($task->start_date);

            // if($task_day->gt($item_day)) {
            //     $day_diff = $item_day->diffInDays($task_day);

            //     $task->duration = $task->duration + $day_diff;
            // }elseif($item_day->eq($task_day)){
            //     // dd([$item_day, $task_day]);
            //     $task->duration = $item['w'];

            // }else{
            //     $day_diff = $item_day->diffInDays($task_day);
            //     $task->duration = $task->duration - $day_diff;
            // }

            $task->duration = $item['w'];
            $task->order = $item['y'];
            $task->start_date = $this->days[$item['x']]['database_date'];
            $task->end_date = Carbon::parse($this->days[$item['x']]['database_date'])->addDays($item['w'] - 1)->format('Y-m-d');
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
