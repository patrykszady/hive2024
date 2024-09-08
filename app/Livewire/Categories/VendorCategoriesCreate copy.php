<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\Attributes\Validate;

use App\Models\Expense;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\VendorCategory;

use App\Livewire\Forms\VendorCategoriesForm;

class VendorCategoriesCreate extends Component
{
    public VendorCategoriesForm $form;

    public Vendor $vendor;

    // public $vendor_category = NULL;
    public $vendor_categories = [];
    public $expense_categories = [];

    public $vendor_expense_categories = [];

    public $showModal = FALSE;

    protected $listeners = ['addCategories'];

    // public function rules()
    // {
    //     return [
    //         'vendor_category' => 'required'
    //     ];
    // }

    public function mount()
    {
        $this->vendor_categories = VendorCategory::all();
        $this->expense_categories = Category::all();
    }

    public function addCategories(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->form->setVendor($vendor);

        $this->vendor_expense_categories =
            Expense::where('vendor_id', $this->vendor->id)
                ->whereYear('date', '>=', 2023)
                ->with('category')
                ->get()
                ->groupBy('category.friendly_primary')
                ->toBase();

        // dd($this->vendor_expense_categories);
        $this->showModal = TRUE;
    }

    public function save()
    {
        $this->form->store();
        $this->dispatch('refreshComponent')->to('categories.categories-index');

        $this->showModal = FALSE;
        $this->dispatch('notify',
            type: 'success',
            content: 'Vendor Categories Created'
        );
    }

    public function render()
    {
        return view('livewire.categories.vendor-create-form');
    }
}
