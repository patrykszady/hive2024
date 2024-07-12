<?php

namespace App\Livewire\Test;

use App\Models\Client;
use Livewire\Component;

class Playground extends Component
{
    public $clients = [];
    public $client_id = NULL;

    public function rules()
    {
        return [
            'client_id' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->clients = Client::all();
    }

    public function updated($field, $value)
    {
        // if($field == 'client_id'){
        //     $this->client_id = $value['id'];
        // }

        // dd($this);
    }

    // public function selected()
    // {
    //     dd($this);
    // }

    public function render()
    {
        if(auth()->user()->id === 1){
            return view('livewire.test.playground');
        }else{
            abort(403);
        }
    }
}
