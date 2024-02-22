<?php

namespace App\Livewire\Tasks;

use Livewire\Component;

use Carbon\Carbon;

class Planner extends Component
{
    public function render()
    {
        $ganttChartTable = new \Helvetitec\LagoonCharts\DataTables\GanttChartTable();

        $ganttChartTable->addTask("test1", "Test1", Carbon::now(), Carbon::now()->copy()->addMonth(), 30, 100, null);
        $ganttChartTable->addTask("test2", "Test2", Carbon::now()->copy()->addMonth(), Carbon::now()->copy()->addMonths(2), 30, 100, "test1");

        $data = $ganttChartTable->__toString(); //IMPORTANT USE __toString() here!

        return view('livewire.tasks.planner', [
            'data' => $data,
        ]);
    }
}
