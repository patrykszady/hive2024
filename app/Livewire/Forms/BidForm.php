<?php

namespace App\Livewire\Forms;

use App\Models\Bid;

use Livewire\Form;
use Livewire\Attributes\Validate;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BidForm extends Form
{
    use AuthorizesRequests;

    // public function setBids($bids)
    // {
    //     $this->bids = $bids;
    //     // dd($bids);
    // }

    public function store()
    {
        // $this->authorize('create', Bid::class);
        $this->component->validate();

        foreach($this->component->bids as $index => $bid){
            $bid->update([
                'amount' => $bid['amount'],
                'project_id' => $this->component->project->id,
            ]);
        }
    }
}
