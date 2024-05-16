<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProjectDistributionsAmount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $project;
    /**
     * Create a new job instance.
     */
    public function __construct($project)
    {
        $this->project = $project;
        // dd($this->project);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $profit = $this->project->finances['profit'];

        foreach($this->project->distributions as $distribution){
            $percent = '.' . $distribution->pivot->percent;
            $amount = round($profit * $percent, 2);

            $this->project->distributions()->updateExistingPivot($distribution, ['amount' => $amount], true);
        }
    }
}
