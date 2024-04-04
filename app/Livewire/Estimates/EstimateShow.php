<?php

namespace App\Livewire\Estimates;

use App\Models\Estimate;
use App\Models\EstimateSection;
use App\Models\EstimateLineItem;

use App\Jobs\SendInitialEstimateEmail;

use Spatie\Browsershot\Browsershot;
use Rmunate\Utilities\SpellNumber;
use Illuminate\Support\Facades\Response;

use Livewire\Component;
use Livewire\Attributes\Title;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EstimateShow extends Component
{
    use AuthorizesRequests;

    public Estimate $estimate;
    public $sections = [];

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'sections.*.name' => 'required',
            'sections.*.items_rearrange' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->sections =
            $this->estimate->estimate_sections
                ->each(function ($item, $key) {
                    $item->items_rearrange = FALSE;
                });

        //11-1-2023 MOVE to EstiamteCreate
        //start with one section and an ADD card/button for line items
        if($this->sections->isEmpty()){
            $this->create_new_section();
            $this->estimate_refresh();
        }
    }

    public function estimate_refresh()
    {
        $this->estimate->refresh();
        $this->sections = $this->estimate->estimate_sections;
    }

    public function create_new_section($name = NULL)
    {
        return EstimateSection::create([
            'estimate_id' => $this->estimate->id,
            'index' => $this->sections->isEmpty() ? 0 : $this->sections->max('index') + 1,
            'name' => $name,
            'total' => 0.00,
            'deleted_at' => NULL
        ]);
    }

    public function sectionAdd()
    {
        $this->create_new_section();

        $this->estimate_refresh();

        $this->dispatch('notify',
            type: 'success',
            content: 'Section Added'
        );
    }

    public function sectionRemove($section_id)
    {
        $section = $this->sections->where('id', $section_id)->first();
        $estimate_line_items = $this->estimate->estimate_line_items()->where('section_id', $section->id)->get();
        foreach($estimate_line_items as $estimate_line_item){
            $estimate_line_item->delete();
        }

        $section->delete();

        $this->estimate_refresh();

        // $duplicate_section_lines = $this->estimate->estimate_line_items->where('section_id', $section_id);

        // foreach($duplicate_section_lines as $duplicate_section_line){
        //     $duplicate_section_line->delete();
        // }
        $this->dispatch('notify',
            type: 'success',
            content: 'Section Removed'
        );
    }

    public function sectionUpdate($section_index)
    {
        $section = $this->sections[$section_index];
        //ignore 'bid_index' attribute when saving
            //OR put    // public $items_rearrange; on Model
        $section->offsetUnset('items_rearrange');
        $section->save();

        // $this->estimate_refresh();

        $this->dispatch('notify',
            type: 'success',
            content: 'Section Name Updated'
        );
    }

    public function itemsRearrange($section_index)
    {
        $section = $this->sections[$section_index];

        if($section->items_rearrange == FALSE){
            $section->items_rearrange = TRUE;
        }else{
            $section->items_rearrange = FALSE;
        }
    }

    public function itemsRearrangeOrder($list)
    {
        foreach($list as $item){
            EstimateLineItem::find($item['value'])->update(['section_index' => $item['order']]);
        }
    }

    public function sectionDuplicate($section_id)
    {
        $line_items = $this->estimate->estimate_line_items()->where('section_id', $section_id)->get();
        $section_to_duplicate = $this->estimate->estimate_sections()->where('id', $section_id)->first();

        $section = $this->create_new_section($section_to_duplicate->name . ' -Copy');

        //create new estimate section
        foreach($line_items as $duplicate_section_line){
            EstimateLineItem::create([
                'estimate_id' => $this->estimate->id,
                'line_item_id' => $duplicate_section_line->line_item_id,
                'section_id' => $section->id,
                'section_index' => $duplicate_section_line->section_index,
                'name' => $duplicate_section_line->name,
                'category' => $duplicate_section_line->category,
                'sub_category' => $duplicate_section_line->sub_category,
                'unit_type' => $duplicate_section_line->unit_type,
                'quantity' => $duplicate_section_line->quantity,
                'cost' => $duplicate_section_line->cost,
                'total' => $duplicate_section_line->total,
                'desc' => $duplicate_section_line->desc,
                'notes' => $duplicate_section_line->notes,
            ]);
        }

        $this->estimate_refresh();

        $this->dispatch('notify',
            type: 'success',
            content: 'Section Duplicated'
        );
    }

    public function getEstimateTotalProperty()
    {
        return $this->sections->sum('total');
    }

    //$type = [estimate, invoice]
    public function print($type)
    {
        if($type == 'estimate'){
            // SendInitialEstimateEmail::dispatch($this->estimate, $this->sections, $type);
            $data = $this->create_pdf($this->estimate, $this->sections, $type);

            $headers =
                array(
                    'Content-Type: application/pdf',
                );

            return Response::download($data[0], $data[1] . '.pdf', $headers);
        }elseif($type == 'invoice'){
            $data = $this->create_pdf($this->estimate, $this->sections, $type);

            $headers =
                array(
                    'Content-Type: application/pdf',
                );

            return Response::download($data[0], $data[1] . '.pdf', $headers);
        }
    }

    public function create_pdf($estimate, $sections, $type)
    {
        $estimate_total = $sections->sum('total');
        $type = ucfirst($type);

        $estimate_total_words =
            SpellNumber::value($estimate_total)->locale('en')
                ->currency('Dollars')
                ->fraction('cents')
                ->toMoney();

        $payments = $estimate->project->payments->where('belongs_to_vendor_id', $estimate->vendor->id);

        $title = $estimate->client->name . ' | ' . $type . ' | ' . $estimate->project->project_name . ' | ' . $estimate->number;
        $title_file = $estimate->client->name . ' - ' . $type . ' - ' . $estimate->project->project_name . ' - ' . $estimate->number;

        $view = view('misc.estimate', compact(['estimate', 'sections', 'payments', 'title', 'estimate_total', 'estimate_total_words', 'type']))->render();
        $location = storage_path('files/pdfs/' . $title_file . '.pdf');

        Browsershot::html($view)
            ->newHeadless()
            ->scale(0.8)
            ->showBrowserHeaderAndFooter()
            // ->headerHtml('Header')
            // ->footerHtml('<span class="pageNumber"></span>')
            //->margins($top, $right, $bottom, $left)
            ->margins(10, 5, 10, 5)
            ->save($location);

        return [$location, $title_file];
    }

    public function delete()
    {
        $estimate = $this->estimate;
        $estimate->delete();

        //con notification ?
        $this->redirectRoute('projects.show', ['project' => $estimate->project->id]);
    }

    #[Title('Estimate')]
    public function render()
    {
        $this->authorize('view', $this->estimate);

        return view('livewire.estimates.show');
    }
}
