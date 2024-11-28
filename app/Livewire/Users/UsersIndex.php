<?php

namespace App\Livewire\Users;

use App\Models\Client;
use App\Models\Vendor;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

class UsersIndex extends Component
{
    public Client $client;
    public Vendor $vendor;
    public $users = [];
    public $view = NULL;
    public $registration = NULL;

    protected $listeners = ['refreshComponent' => '$refresh', 'testUsers'];

    public $view_text = [
        'card_title' => 'Users',
        'button_text' => 'Add User',
    ];

    // #[Computed]
    // public function users()
    // {
    //     return $this->client->users;
    // }
    // #[Computed]
    // public function users()
    // {
    //     dd(Client::findOrFail($this->client->id)->users);
    //     return Client::findOrFail($this->client->id)->users;
    // }

    public function testUsers()
    {
        // dd($this);
        // $this->client->fresh();
        //         // dd($this->users);
        // if($this->view == 'clients.show'){
        //     $this->view_text['card_title'] = "Client Members";
        //     $this->users = $this->client->users;
        // }elseif($this->view == 'vendors.show'){
        //     $this->view_text['card_title'] = "Team Members";
        //     $this->users = $this->vendor->users()->employed()->get();
        // }
        $this->mount();
        $this->render();
        // $this->users = Client::findOrFail($this->client->id)->users()->get();
        // $this->client->fresh();
        // // $this->client->users = $this->client->users()->fresh();
        // dd($this->client->users);
    }

    public function mount()
    {
        if($this->view == 'clients.show'){
            $this->view_text['card_title'] = "Client Members";
            $this->users = Client::findOrFail($this->client->id)->users;
        }elseif($this->view == 'vendors.show'){
            $this->view_text['card_title'] = "Team Members";
            $this->users = $this->vendor->users()->employed()->get();
        }else{
            dd($this);
        }
    }

    public function add_user()
    {
        if($this->view == 'clients.show'){
            $this->dispatch('newMember', model: 'client', model_id: $this->client->id);
        }elseif($this->view == 'vendors.show'){
            $this->dispatch('newMember', model: 'vendor', model_id: $this->vendor->id);
        }
    }

    public function render()
    {
        return view('livewire.users.index');
    }
}
