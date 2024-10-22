<?php

namespace App\Livewire\LineItems;

use App\Models\Estimate;
use App\Models\LineItem;

use Livewire\Component;
use App\Livewire\Forms\EstimateLineItemForm;

class EstimateLineItemCreate extends Component
{
    protected $listeners = ['addToEstimate', 'editOnEstimate'];

    public Estimate $estimate;

    public EstimateLineItemForm $form;

    public $search = '';

    public $section_id = NULL;
    public $line_item_id = NULL;
    public $edit_line_item = FALSE;
    public $line_items = [];
    public $estimate_line_item = [];
    public $section_item_count = NULL;


    public $view_text = [
        'card_title' => 'Add Line Item',
        'button_text' => 'Add Item',
        'form_submit' => 'save',
    ];

    public function rules()
    {
        return [
            'line_item_id' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->line_items = LineItem::get()->keyBy('id');
    }

    public function updated($field, $value)
    {
        $this->validateOnly($field);
        if(in_array($field, ['form.quantity', 'form.cost'])){
            $this->form->total = $this->getTotalLineItemProperty();
        }
    }

    public function selected_line_item($line_item_id)
    {
        $this->line_item_id = $line_item_id;
        $this->form->setLineItem($this->line_items[$line_item_id]);
        $this->search = $this->form->line_item->name;
        $this->form->total = $this->getTotalLineItemProperty();
    }

    public function getTotalLineItemProperty()
    {
        // $total = 0;
        // $total +=
        // dd(isset($this->form->quantity));
        if($this->form->quantity == 0){
            $quantity = 0;
        }else{
            $quantity = $this->form->quantity;
        }

        if($this->form->cost == 0){
            $cost = 0;
        }else{
            $cost = $this->form->cost;
        }

        $total = $quantity * $cost;
        $total = number_format((float)$total, 2, '.', '');

        return $total;
    }

    public function removeFromEstimate()
    {
        $this->estimate_line_item->delete();
        $this->dispatch('refreshComponent')->to('estimates.estimate-show');
    }

    public function editOnEstimate($estimate_line_item_id)
    {
        $this->form->reset();
        $this->estimate_line_item = $this->estimate->estimate_line_items()->findOrFail($estimate_line_item_id);

        $this->form->setEstimateLineItem($this->estimate_line_item);
        $this->form->total = $this->getTotalLineItemProperty();

        $this->line_item_id = $this->estimate_line_item->line_item_id;

        $this->view_text = [
            'card_title' => 'Edit Line Item',
            'button_text' => 'Edit Item',
            'form_submit' => 'edit',
        ];

        $this->section_id = $this->estimate_line_item->section->id;
        $this->edit_line_item = TRUE;
        $this->search = $this->estimate_line_item->name;
        $this->modal('estimate_line_item_form_modal')->show();
    }

    public function addToEstimate($section_id)
    {
        $section = $this->estimate->estimate_sections()->findOrFail($section_id);
        $this->section_item_count = $section->estimate_line_items->count();
        $this->edit_line_item = FALSE;
        $this->search = '';
        $this->estimate_line_item = NULL;
        $this->line_item_id = NULL;
        $this->form->reset();

        $this->view_text = [
            'card_title' => 'Add Line Item',
            'button_text' => 'Add Item',
            'form_submit' => 'save',
        ];

        $this->section_id = $section->id;

        $this->modal('estimate_line_item_form_modal')->show();
    }

    public function edit()
    {
        $this->form->update();

        $this->modal('estimate_line_item_form_modal')->close();
        $this->dispatch('refreshComponent')->to('estimates.estimate-show');
    }

    public function save()
    {
        $this->form->store();

        $this->modal('estimate_line_item_form_modal')->close();
        $this->search = '';
        $this->section_item_count = NULL;
        $this->dispatch('refreshComponent')->to('estimates.estimate-show');
    }

    public function render()
    {
        return view('livewire.line-items.estimate-line-item-create', [
            'line_items_test' => LineItem::orderBy('created_at', 'DESC')->where('name', 'like', '%' . $this->search . '%')->orWhere('desc', 'like', '%' . $this->search . '%')->get(),
        ]);
    }
}
