<?php

namespace App\Livewire\Sheets;

use App\Models\Sheet;
use App\Models\Expense;
use App\Models\Check;
use App\Models\Payment;
use App\Models\Vendor;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Spatie\SimpleExcel\SimpleExcelWriter;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;

class SheetShow extends Component
{
    use AuthorizesRequests;

    public $year = '';
    public $cost_of_labor_sum = 0;
    public $cost_of_materials_sum = 0;
    public $general_expenses = 0;
    public $revenue = 0;
    public $cost_of_materials_vendors = [];
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

        //material or NOT GENERAL/ADMINISTRATIVE
        $material_vendor_ids = Vendor::where('sheets_type', 'Materials')->pluck('id');
        $sub_vendors_ids = Vendor::whereNot('business_type', 'Retail')->pluck('id');

        $this->general_expense_categories =
            Expense::whereYear('date', $this->year)
                ->whereNotIn('category_id', [112,113,114,115,116,117,118,119,120,121,122, 123,124,125,126,127,128])
                ->whereNotIn('vendor_id', array_merge($material_vendor_ids->toArray(), $sub_vendors_ids->toArray()))
                ->with(['category', 'vendor'])
                ->get()
                // ->groupBy(['category.friendly_detailed', 'vendor.busienss_name'])
                ->groupBy('category.friendly_primary')
                ->toBase();

        //->sum('amount')
        $this->cost_of_materials_vendors = Expense::whereYear('date', $this->year)->whereIn('vendor_id', $material_vendor_ids)->with(['vendor'])->get()->groupBy('vendor.business_name')->toBase();
        $this->cost_of_materials_sum = Expense::whereYear('date', $this->year)->whereIn('vendor_id', $material_vendor_ids)->sum('amount');
        $this->general_expenses = Expense::whereYear('date', $this->year)->whereNotIn('vendor_id', array_merge($material_vendor_ids->toArray(), $sub_vendors_ids->toArray()))->whereNotIn('category_id', [123,124,125,126,127,128])->sum('amount');
    }

    public function export_csv()
    {
        $border = new Border(
            new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THICK, Border::STYLE_SOLID)
        );
        $border_thin = new Border(
            new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
        );

        $writer = SimpleExcelWriter::create('test-' . mt_rand(0,19999999) . '.xlsx')
            ->addHeader([]);

            $writer->addRow([
                'category' => NULL,
                'sub_category' => 'COST OF MATERIALS',
                'vendor' => NULL,
                'amount' => money($this->cost_of_materials_sum)
            ], (new Style())->setFontBold()->setBorder($border));

            foreach($this->cost_of_materials_vendors as $vendor_name => $cost_of_materials_vendor){
                $writer->addRow([
                    'category' => NULL,
                    'sub_category' => NULL,
                    'vendor' => $vendor_name,
                    'amount' => money($cost_of_materials_vendor->sum('amount')),
                ]);
            }

            $writer->addRow([
                'category' => NULL,
                'sub_category' => NULL,
                'vendor' => NULL,
                'amount' => NULL
            ]);

            $writer->addRow([
                'category' => NULL,
                'sub_category' => 'COST OF LABOR',
                'vendor' => NULL,
                'amount' => money($this->cost_of_labor_sum)
            ], (new Style())->setFontBold()->setBorder($border));

            foreach($this->cost_of_labor_vendors as $vendor_name => $cost_of_labor_vendor){
                $writer->addRow([
                    'category' => NULL,
                    'sub_category' => NULL,
                    'vendor' => $vendor_name,
                    'amount' => money($cost_of_labor_vendor->sum('amount')),
                ]);
            }

            $writer->addRow([
                'category' => NULL,
                'sub_category' => NULL,
                'vendor' => NULL,
                'amount' => NULL
            ]);

            foreach($this->general_expense_categories as $category_primary_name => $general_expense_category){
                $writer->addRow([
                    'category' => $category_primary_name,
                    'sub_category' => NULL,
                    'vendor' => NULL,
                    'amount' => money($general_expense_category->sum('amount')),
                ], (new Style())->setFontBold()->setBorder($border));

                foreach($general_expense_category->groupBy('category.friendly_detailed') as $category_friendly_detailed => $category_friendly_expenses){
                    $writer->addRow([
                        'category' => NULL,
                        'sub_category' => $category_friendly_detailed,
                        'vendor' => NULL,
                        'amount' => money($category_friendly_expenses->sum('amount')),
                    ], (new Style())->setFontItalic()->setBorder($border_thin));

                    foreach($category_friendly_expenses->groupBy('vendor.busienss_name') as $vendor_name => $general_expense_vendor_expenses){
                        $writer->addRow([
                            'category' => NULL,
                            'sub_category' => NULL,
                            'vendor' => $vendor_name,
                            'amount' => money($general_expense_vendor_expenses->sum('amount')),
                        ]);
                    }
                }
            }


    }

    #[Title('Sheet')]
    public function render()
    {
        $this->authorize('viewAny', Sheet::class);
        return view('livewire.sheets.show');
    }
}
