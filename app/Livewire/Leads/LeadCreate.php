<?php

namespace App\Livewire\Leads;

use App\Models\Lead;
use App\Models\LeadStatus;

use Flux;

use Livewire\Component;

class LeadCreate extends Component
{
    public $lead = NULL;
    public $user = NULL;
    public $full_name = NULL;

    public $date = NULL;
    public $lead_status = NULL;

    protected $listeners = ['editLead'];
    public $view_text = [
        'card_title' => 'Create Expense',
        'button_text' => 'Create',
        'form_submit' => 'save',
    ];

    public function rules()
    {
        return [
            'lead_status' => 'required',
            'lead.message' => 'required',
            // 12-30-2024 MAKE THIS A CHAT/COMMET/MSG section
            'lead.notes' => 'nullable',
            'lead.address' => 'required',
            'lead.origin' => 'required',
            'full_name' => 'nullable',
            'date' => 'required',
        ];
    }

    public function editLead(Lead $lead)
    {
        $this->lead = $lead;

        $this->lead->message = $this->lead->lead_data->message;
        $this->lead->address = $this->lead->lead_data->address;
        $this->lead->notes = $this->lead->notes;
        $this->lead->origin = $this->lead->origin;
        $this->date = $this->lead->date->format('Y-m-d');
        $this->user = $this->lead->user;
        $this->lead_status = $this->lead->last_status ? $this->lead->last_status->title : NULL;

        if(!is_null($this->user)){
            // $this->user->full_name = !is_null($this->user) ? $this->user->full_name : 'Create User';
            $this->full_name = $this->user->full_name;
        }else{
            $this->full_name = $this->lead->lead_data['name'];
        }

        $this->view_text = [
            'card_title' => 'Edit Lead',
            'button_text' => 'Update',
            'form_submit' => 'edit',
        ];

        $this->modal('lead_form_modal')->show();
    }

    public function edit()
    {
        $lead = Lead::findOrFail($this->lead->id);
        $lead->lead_data['address'] = $this->lead->address;
        $lead->notes = $this->lead->notes;
        $lead->save();

        $lead->statuses()->create([
            'title' => $this->lead_status,
            'belongs_to_vendor_id' => $lead->belongs_to_vendor_id,
        ]);

        $this->lead_status = NULL;
        $this->modal('lead_form_modal')->close();
        $this->dispatch('refreshComponent')->to('leads.leads-index');

        Flux::toast(
            duration: 5000,
            position: 'top right',
            variant: 'success',
            heading: 'Lead Updated.',
            // route / href / wire:click
            text: '',
        );
    }

    public function render()
    {
        return view('livewire.leads.form');
    }
}
