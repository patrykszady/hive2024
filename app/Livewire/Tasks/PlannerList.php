<?php

namespace App\Livewire\Tasks;

use App\Models\Project;

use Livewire\Component;
use Livewire\Attributes\Title;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class PlannerList extends Component
{
    public $projects = [];
    public $days = [];
    public $week = '';

    // protected $listeners = ['refresh_planner'];

    protected $queryString = [
        'week' => ['except' => ''],
    ];

    public function mount()
    {
        $this->projects = Project::with('tasks')->status(['Active', 'Scheduled', 'Service Call', 'Invited']);

        if($this->week){
            //5-24-2026 must be Y-m-d format, else go to else below
            $monday = $this->week;
        }else{
            $monday = today()->format('Y-m-d');
        }

        $this->set_week_days($monday);
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
                'formatted_date' => $confirmed_date->format('D, m/d'),
                'is_today' => $confirmed_date == today()
            ];
        }
    }

    // public function refresh_planner($days = NULL)
    // {
    //     if(!is_null($days)){
    //         $this->days = $days;
    //     }

    //     $this->mount();
    //     $this->render();
    // }

    //render method not needed if view and component follow a convention
    #[Title('Planner')]
    public function render()
    {
        return view('livewire.tasks.planner-list');
    }
}
