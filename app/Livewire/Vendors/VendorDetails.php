<?php

namespace App\Livewire\Vendors;

use App\Models\Vendor;
use Livewire\Component;

class VendorDetails extends Component
{
    public Vendor $vendor;
    public $registration = FALSE;
    // public $accordian = 'CLOSED';

    //'refreshComponent' => '$refresh',
    protected $listeners = ['refresh'];

    public function refresh()
    {
        $this->registration = FALSE;
        $this->render();
    }

    public function render()
    {
        return view('livewire.vendors.vendor-details');
    }
}
