<?php

namespace App\Livewire\Sheets;

use App\Models\Expense;
use App\Models\Check;
use App\Models\Payment;
use App\Models\Vendor;

use Livewire\Component;
use Livewire\Attributes\Title;

class SheetsIndex extends Component
{
    public $year = 2024;
    public $cost_of_labor = 0;
    public $cost_of_materials = 0;
    public $general_expenses = 0;
    public $revenue = 0;
    public $general_expenses_categories = [];

    protected $queryString = [
        'year' => ['except' => ''],
    ];

    public function mount()
    {
        $vendor_admins = auth()->user()->vendor->users()->employed()->wherePivot('role_id', 1)->pluck('user_id')->toArray();
        // dd($vendor_admins);
        //1-22-24 do not show CASH when preparing TAXES
        //->whereDoesntHave('transaction')
        //->whereNotNull('vendor_id')->where('vendor_id', '!=', auth()->user()->vendor->id)
        // ->whereNotIn('user_id', $vendor_admins)
        // ->whereNull('user_id')->orWhereNotIn('user_id', $vendor_admins)
        //>where('vendor_id', '!=', 1)
        // ::whereYear('date', 2023)->where('vendor_id', '!=', auth()->user()->vendor->id)->orWhereNotIn('user_id', $vendor_admins)->pluck('user_id');

        $this->revenue = (float) Payment::whereYear('date', $this->year)->sum('amount');

        $this->cost_of_labor =
            Check::
                whereYear('date', $this->year)
                ->where(function($query) use($vendor_admins){
                    $query->whereNotIn('user_id', $vendor_admins)->orWhere('user_id', NULL);
                })
                ->sum('amount');

        $material_vendor_ids = Vendor::where('sheets_type', 'Materials')->pluck('id');
        $sub_vendors_ids = Vendor::where('business_type', 'Sub')->pluck('id');
        // Expense::whereYear('date', $this->year)->whereNotIn('vendor_id', array_merge($material_vendor_ids->toArray(), $sub_vendors_ids->toArray()))
        // ->where('category_id', 188)
        // ->get()
        // ->groupBy('category_id');
        $this->general_expenses_categories =
            Expense::whereYear('date', $this->year)->whereNotIn('vendor_id', array_merge($material_vendor_ids->toArray(), $sub_vendors_ids->toArray()))
                ->with('category')
                ->get()
                ->groupBy('category.friendly_detailed')
                ->toBase();

        $this->cost_of_materials = Expense::whereYear('date', $this->year)->whereIn('vendor_id', $material_vendor_ids)->sum('amount');

        $this->general_expenses = Expense::whereYear('date', $this->year)->whereNotIn('vendor_id', array_merge($material_vendor_ids->toArray(), $sub_vendors_ids->toArray()))->sum('amount');
    }

    #[Title('Sheets')]
    public function render()
    {
        return view('livewire.sheets.index');
    }
}
