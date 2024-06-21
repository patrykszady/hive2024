<?php

namespace App\Livewire\Projects;

use App\Models\Vendor;

use Livewire\Component;

class ProjectVendors extends Component
{
    public $vendor_id;
    public $vendors = [];
    public $showModal = FALSE;

    protected $listeners = ['addVendors'];

    public function rules()
    {
        return [
            'vendor_id' => 'required',
        ];
    }

    public function mount()
    {
        $this->vendors = auth()->user()->vendor->vendors()->whereJsonContains('registration', ['registered' => true])->get();
    }

    public function addVendors()
    {
        $this->showModal = TRUE;
    }

    public function save()
    {
        dd($this->vendor_id);
        //project status
        //project vendor
    }

    public function render()
    {
        // dd('here in livewire.projects.project-vendors');
        return view('livewire.projects.project-vendors');
    }
}
