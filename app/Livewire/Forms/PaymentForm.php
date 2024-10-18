<?php

namespace App\Livewire\Forms;

use App\Models\Payment;

use Livewire\Attributes\Rule;
use Livewire\Form;

class PaymentForm extends Form
{
    #[Rule('required|date|before_or_equal:today|after:2017-01-01')]
    public $date = NULL;

    #[Rule('required')]
    public $invoice = NULL;

    #[Rule('nullable')]
    public $note = NULL;

    public function store()
    {
        $this->validate();

        $parent_payment_id = NULL;
        foreach($this->component->projects->where('amount', '!=', NULL) as $key => $project){
            if($key == 0){
                $parent_payment_id = NULL;
            }else{
                $parent_payment_id = $parent_payment_id;
            }

            $payment = Payment::create([
                'amount' => $project->amount,
                'project_id' => $project->id,
                'date' => $this->date,
                'reference' => $this->invoice,
                'belongs_to_vendor_id' => auth()->user()->primary_vendor_id,
                'note' => $this->note,
                'created_by_user_id' => auth()->user()->id,
                'parent_client_payment_id' => $parent_payment_id,
            ]);

            $parent_payment_id = $payment->id;
        }

        return $payment;
    }
}
