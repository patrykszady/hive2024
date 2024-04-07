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
    public $day_tasks = [];
    public $days = [];
    public $projects = [];
    public $week = '';

    //'refreshComponent' => '$refresh',
    protected $listeners = ['refresh'];

    protected $queryString = [
        'week' => ['except' => ''],
    ];

    public function mount()
    {
        $this->projects = Project::status(['Active', 'Scheduled'])->keyBy('id')->sortByDesc('last_status.start_date');

        if($this->week){
            //must be Y-m-d format, else go to else
            $monday = $this->week;
        }else{
            $monday = today()->format('Y-m-d');
        }

        $this->set_week_days($monday);

        $this->day_tasks = $this->refresh_day_tasks();
    }

    public function refresh()
    {
        $this->day_tasks = $this->refresh_day_tasks();
    }

    public function refresh_day_tasks()
    {
        return Task::orderBy('position')->with(['vendor', 'user'])->whereBetween('start_date', [$this->days[0]['database_date'], $this->days[5]['database_date']])->get()->groupBy('project_id', 'start_date')->toBase();
    }

    public function set_week_days($monday)
    {
        //carbon period
        $days = new \DatePeriod(
            Carbon::parse($monday)->startOfWeek(Carbon::MONDAY),
            CarbonInterval::day(),
            Carbon::parse($monday)->startOfWeek(Carbon::MONDAY)->endOfWeek(Carbon::SATURDAY)
        );

        $this->days = [];
        foreach($days as $confirmed_date){
            $this->days[] = [
                'database_date' => $confirmed_date->format('Y-m-d'),
                'formatted_date' => $confirmed_date->format('D, m/d')
            ];
        }
    }

    public function taskRearrange($list)
    {
        foreach($list as $day_items)
        {
            foreach($day_items['items'] as $item)
            {
                $task = Task::findOrFail($item['value']);
                $task->start_date = $day_items['value'];
                $task->position = $item['order'];
                $task->save();
            }
        }

        $this->day_tasks = $this->refresh_day_tasks();
        $this->render();
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

        $this->set_week_days(today()->format($monday));
        $this->day_tasks = $this->refresh_day_tasks();
    }

    #[Title('Planner')]
    public function render()
    {
        return view('livewire.tasks.planner');
    }
}
