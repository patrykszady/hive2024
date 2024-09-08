<?php

namespace App\Livewire\Categories;

use Livewire\Component;

use App\Models\Vendor;

class CategoriesIndex extends Component
{
    public $vendors = [];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        //where has expenses from 2023
        //->with('vendor_categories')
        $this->vendors =
            Vendor::where('business_type', 'Retail')
                ->whereHas('expenses', function ($query){
                    $query->whereYear('date', 2023);
                })
                ->orderBy('business_name', 'ASC')
                ->get();
        // dd($this->vendors->first());
    }

    public function render()
    {
        return view('livewire.categories.index');
    }
}
