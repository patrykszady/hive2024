<?php

namespace App\Livewire\Estimates;

use App\Models\Project;
use App\Models\Estimate;

use Livewire\Component;

class EstimateCombine extends Component
{
    public Project $project;
    public $estimate_id = NULL;
    public $estimate = NULL;

    public $modal_show = FALSE;

    protected $listeners = ['combineModal'];

    protected function rules()
    {
        return [
            'estimate_id' => 'required',
        ];
    }

    public function combineModal($existing_estimate_id)
    {
        // dd($existing_estimate_id);
        $this->estimate = Estimate::findOrFail($existing_estimate_id);
        // dd($this->project->estimates);
        // $this->estimate = $estimate;
        $this->modal_show = TRUE;
    }

    public function save()
    {
        $this->validate();

        //get current estimate and duplicate sections and line_items
        $new_estimate = Estimate::findOrFail($this->estimate_id);

        foreach($this->estimate->estimate_sections as $section){
            $new_section = $section->replicate();
            $new_section->estimate_id = $new_estimate->id;
            $new_section->bid_id = NULL;
            $new_section->save();

            foreach($this->estimate->estimate_line_items->where('section_id', $section->id) as $line_item){
                $line_item->unsetEventDispatcher();
                $new_line_item = $line_item->replicate();
                $new_line_item->estimate_id = $new_estimate->id;
                $new_line_item->section_id = $new_section->id;
                $new_line_item->save();
            }
        }

        $this->dispatch('notify',
            type: 'success',
            content: 'Estimate Duplicated',
            route: 'estimates/' . $new_estimate->id
        );
    }

    public function render()
    {
        return view('livewire.estimates.combine');
    }
}
