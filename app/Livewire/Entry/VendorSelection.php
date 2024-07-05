<?php

namespace App\Livewire\Entry;

use App\Models\User;
use App\Models\Check;
use App\Models\Project;
use App\Models\Payment;
use App\Models\ProjectStatus;
use App\Models\Vendor;

use Livewire\Component;
use Livewire\Attributes\Title;

class VendorSelection extends Component
{
    public $user;
    public $vendor;
    public $vendor_id = NULL;
    public $vendor_name = NULL;

    public $vendors = [];
    public $clients = [];

    public function mount()
    {
        $this->user = auth()->user();
        //if env = production vs dev
        //where not user removed / where end_date is null
        $this->vendors = $this->user->vendors()
            // , '1099'
            ->whereIn('vendors.business_type', ['Sub'])
            ->wherePivot('is_employed', 1)
            ->withoutGlobalScopes()
            ->orderBy('vendors.business_type')
            ->get();

        $this->clients = $this->user->clients()->get();
    }

    public function updatedVendorId($vendor_id)
    {
        // $this->vendor = Vendor::withoutGlobalScopes()->findOrFail($vendor_id);
        $this->vendor = $this->vendors->where('id', $vendor_id)->first();

        if($this->vendor->registration['registered']){
            $button_text = 'Login to ';
        }else{
            $button_text = 'Register ';
        }

        $this->vendor_name = $button_text . $this->vendor->business_name;
        $this->vendor_id = $this->vendor->id;
    }

    //change primary_vendor_id on User::id
    public function save()
    {
        // dd($this->vendor->registration['registered']);
        $this->user->primary_vendor_id = $this->vendor_id;
        $this->user->save();

        // 3-30-2023 This should be a middleware
        if($this->vendor->registration['registered']){
            return redirect()->route('dashboard');
        }else{
            return redirect()->route('vendor_registration', $this->vendor_id);
        }
    }

    #[Title('Vendor Selection')]
    public function render()
    {
        return view('livewire.entry.vendor-selection', [
        ]);
    }
}
