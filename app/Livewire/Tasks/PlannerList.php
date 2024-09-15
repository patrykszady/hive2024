<?php

namespace App\Livewire\Tasks;

use App\Models\Project;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class PlannerList extends Component
{
    public $projects = [];
    // public $days = [];
    public $week = '';

    protected $listeners = ['refreshComponent' => '$refresh', 'refresh_planner'];

    protected $queryString = [
        'week' => ['except' => ''],
    ];

    public function mount()
    {
        $this->projects = Project::with('tasks')
            ->status(['Active', 'Scheduled', 'Service Call', 'Invited'])
            ->sortBy([['last_status.title', 'asc'], ['last_status.start_date', 'desc']]);
    }

    public function set_week_days($monday)
    {
        $days = new \DatePeriod(
            Carbon::parse($monday)->startOfWeek(Carbon::MONDAY),
            CarbonInterval::day(),
            Carbon::parse($monday)->startOfWeek(Carbon::MONDAY)->endOfWeek(Carbon::SUNDAY)
        );

        $days_formatted = [];
        foreach($days as $confirmed_date){
            //need to account for saturday&sunday / days off
            $days_formatted[] = [
                'database_date' => $confirmed_date->format('Y-m-d'),
                'formatted_date' => $confirmed_date->format('D, m/d'),
                'is_today' => $confirmed_date == today()
            ];
        }

        return $days_formatted;
    }

    #[Computed]
    public function days()
    {
        if($this->week){
            //5-24-2024 must be Y-m-d format, else go to else below
            $monday = $this->week;
        }else{
            $monday = today()->format('Y-m-d');
        }

        return $this->set_week_days($monday);
    }

    // public function refresh_planner($days = NULL)
    // {
    //     if(!is_null($days)){
    //         $this->days = $days;
    //     }

    //     $this->mount();
    //     $this->render();
    // }
    public function refresh_planner()
    {
        // $this->hydrate();
    }

    //render method not needed if view and component follow a convention
    #[Title('Planner')]
    public function render()
    {
        return view('livewire.tasks.planner-list');
    }
}
