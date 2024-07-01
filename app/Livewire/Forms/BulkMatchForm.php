<?php

namespace App\Livewire\Forms;

use App\Models\TransactionBulkMatch;

use Livewire\Attributes\Validate;
use Livewire\Form;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BulkMatchForm extends Form
{
    use AuthorizesRequests;

    public ?TransactionBulkMatch $match;
    // 'distribution_id' => 'required_unless:split,true',
    // 'vendor_amount_group.*.checkbox' => 'nullable',

    #[Validate('required')]
    public $vendor_id = NULL;

    //required_without:any_amount
    #[Validate('required_if:any_amount,false|nullable|sometimes|numeric|regex:/^-?\d+(\.\d{1,2})?$/')]
    public $amount = NULL;

    // required_with:amount|
    #[Validate('nullable')]
    public $distribution_id = NULL;

    #[Validate('nullable')]
    public $any_amount = FALSE;

    #[Validate('required_if:any_amount,false|nullable')]
    public $amount_type = '=';

    #[Validate('nullable')]
    public $desc = NULL;

    public function setMatch(TransactionBulkMatch $match)
    {
        $this->match = $match;
        $this->vendor_id = $match->vendor_id;
        $this->amount = $match->amount;
        $this->distribution_id = $match->distribution_id;
        $this->any_amount = $match->any_amount;
        $this->amount_type = $match->options['amount_type'];
        $this->desc = $match->options['desc'];
    }

    public function options()
    {
        //any_amount isset? $amount = NULL, NULL = ANY
        if($this->any_amount == TRUE){
            // $amount = NULL;
            $options['amount_type'] = NULL;
        }else{
            // $amount = $this->amount;
            $options['amount_type'] = $this->amount_type;
        }

        if($this->desc){
            $options['desc'] = $this->desc;
        }else{
            $options['desc'] = NULL;
        }

        if(!empty($this->component->bulk_splits)){
            $options['splits'] = [];

            foreach($this->component->bulk_splits as $index => $split){
                //2 decimals required for percent %
                $options['splits'][$index]['amount'] = $split['amount_type'] == '%' ? '.' . preg_replace('/\./', '', $split['amount']) : $split['amount'];
                $options['splits'][$index]['amount_type'] = $split['amount_type'];
                $options['splits'][$index]['distribution_id'] = $split['distribution_id'];
            }
        }

        return $options;
    }

    public function update()
    {
        $this->authorize('create', TransactionBulkMatch::class);
        $this->validate();

        $options = $this->options();

        $this->match->update([
            'amount' => $this->amount,
            'vendor_id' => $this->vendor_id,
            'distribution_id' => $this->distribution_id,
            'options' => $options,
        ]);
    }

    public function store()
    {
        $this->authorize('create', TransactionBulkMatch::class);
        $this->validate();

        $options = $this->options();
        
        //create new BulkMatch ...
        $bulk_match =
            TransactionBulkMatch::create([
                'amount' => $this->amount,
                'vendor_id' => $this->vendor_id,
                'distribution_id' => $this->distribution_id,
                'options' => $options,
                'belongs_to_vendor_id' => auth()->user()->vendor->id,
            ]);

        // //update
        // if($this->match){
        //     $this->match->update([
        //         'amount' => $this->amount,
        //         'vendor_id' => $this->vendor_id,
        //         'distribution_id' => $this->distribution_id,
        //         'options' => $options,
        //     ]);
        // }else{
        //     //save
        // }
    }
}
