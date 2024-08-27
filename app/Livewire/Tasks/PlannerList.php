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

    public function mount()
    {
        //get this week
        $days = new \DatePeriod(
            Carbon::now()->startOfWeek(Carbon::MONDAY),
            CarbonInterval::day(),
            Carbon::now()->endOfWeek(Carbon::SUNDAY)
        );

        $this->days = collect();
        foreach($days as $day){
            //need to account for saturday&sunday / days off
            $this->days[] = collect([
                'database_date' => $day->format('Y-m-d'),
                'formatted_date' => $day->format('D, m/d'),
                'is_today' => $day == today(),
            ]);
        }
        //->where('is_today', false)
        // dd($this->days->first()['is_today']);

        //tasks where between week
        $this->projects =
            Project::
                // when(!is_null($this->single_project_id), function ($query, $item) {
                //     return $query->where('id', $this->single_project_id);
                // })
                with(['tasks' => function($query) {
                    $query->whereBetween('start_date', [$this->days[0]['database_date'], $this->days[6]['database_date']])->orWhereBetween('end_date', [$this->days[0]['database_date'], $this->days[6]['database_date']]);
                }])
                ->status(['Active', 'Scheduled', 'Service Call', 'Invited'])
                ->sortBy([['last_status.title', 'asc'], ['last_status.start_date', 'desc']])
                ->take(12)
                ->values();

        // dd($this->projects->first());
    }

    #[Title('Schedule')]
    public function render()
    {
        return view('livewire.tasks.planner-list');
    }
}
