<?php

namespace App\Livewire\Sheets;

use App\Models\Sheet;
use App\Models\Expense;
use App\Models\Check;
use App\Models\Payment;
use App\Models\Vendor;
use App\Models\VendorCategory;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SheetShow extends Component
{
    use AuthorizesRequests;

    public $year = '';
    public $cost_of_labor_sum = 0;
    public $cost_of_materials = 0;
    public $general_expenses = 0;
    public $revenue = 0;
    public $cost_of_labor_vendors = [];
    public $general_expense_categories = [];

    protected $queryString = [
        'year' => ['except' => ''],
    ];

    public function mount()
    {
        //08/23/2024 move to middleware .. somehwere else in the onion... not here!
        if($this->year == ''){
            return(redirect('sheets'));
        }


        $vendor_admins = auth()->user()->vendor->users()->employed()->wherePivot('role_id', 1)->pluck('user_id')->toArray();
        // dd($vendor_admins);

        //->whereDoesntHave('transaction')
        //->whereNotNull('vendor_id')->where('vendor_id', '!=', auth()->user()->vendor->id)
        // ->whereNotIn('user_id', $vendor_admins)
        // ->whereNull('user_id')->orWhereNotIn('user_id', $vendor_admins)
        //>where('vendor_id', '!=', 1)
        // ::whereYear('date', 2023)->where('vendor_id', '!=', auth()->user()->vendor->id)->orWhereNotIn('user_id', $vendor_admins)->pluck('user_id');

        //1-22-24 do not show CASH when preparing TAXES
        $this->revenue = (float) Payment::whereYear('date', $this->year)->whereHas('project', function ($query) {
            // $query->status('VIEW ONLY');
            // $query->where('last_status', 'VIEW_ONLY');
            // $query->with('last_status')->where('last_status.title', '!=', 'VIEW ONLY');
            // $query->with(['statuses' => function($query) {
            //     return $query;
            // $query->with(['statuses' => function ($query){
            //     return $query->first();
            //   }]);
            // }]);
            // return $query->status(['Active']);
            $query->whereHas('last_status', function ($query) {
                // dd($query->where('title', '!=', 'VIEW ONLY')->first());
                $query->where('title', '!=', 'VIEW ONLY');
            });
            })->sum('amount');

        $cost_of_labor =
            Check::
                //where check cleared account, not when entered
                whereYear('date', $this->year)
                ->whereNot('check_type', 'Cash')
                // ->where(function($query) use($vendor_admins){
                //     $query->whereNotIn('user_id', $vendor_admins)->orWhere('user_id', NULL);
                // })
                ->whereHas('vendor', function ($query) {
                    //->where('business_name', 'Jesus De La Torre')
                    $query->where('business_type', '!=', 'Retail')->where('id', '!=', auth()->user()->vendor->id);
                });
                // ->get()
                // ->groupBy('vendor.business_name');

        $this->cost_of_labor_vendors = $cost_of_labor->get()->groupBy('vendor.business_name')->toBase();
        $this->cost_of_labor_sum = $cost_of_labor->get()->sum('amount');

        $material_vendor_ids = Vendor::where('sheets_type', 'Materials')->pluck('id');
        $sub_vendors_ids = Vendor::whereNot('business_type', 'Retail')->pluck('id');

        // $this->general_expense_categories = VendorCategory::with('vendors')->get()->groupBy('friendly_detailed')->toBase();
        // dd($this->general_expense_categories);

        // $this->general_expense_categories = Vendor::whereHas('vendor_categories')->get();


        $this->general_expense_categories =
            VendorCategory::with(['vendors' => function ($query){
                $query->with(['expenses' => function ($query){
                    $query->whereYear('date', $this->year);
                }]);
            }])
            ->get()
            ->keyBy('friendly_detailed');

        // dd($this->general_expense_categories);

        // $this->general_expense_categories =
        //     Expense::whereYear('date', $this->year)
        //         ->whereNotIn('vendor_id', array_merge($material_vendor_ids->toArray(), $sub_vendors_ids->toArray()))
        //         ->with(['category', 'vendor'])
        //         ->get()
        //         // ->groupBy(['category.friendly_detailed', 'vendor.busienss_name'])
        //         ->groupBy('category.friendly_detailed')
        //         ->toBase();



        $this->cost_of_materials = Expense::whereYear('date', $this->year)->whereIn('vendor_id', $material_vendor_ids)->sum('amount');
        $this->general_expenses = Expense::whereYear('date', $this->year)->whereNotIn('vendor_id', array_merge($material_vendor_ids->toArray(), $sub_vendors_ids->toArray()))->sum('amount');
    }

    #[Title('Sheet')]
    public function render()
    {
        $this->authorize('viewAny', Sheet::class);
        return view('livewire.sheets.show');
    }
}
