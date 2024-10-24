<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Client;

use App\Livewire\Forms\UserForm;
use App\Livewire\Clients\ClientCreate;
// use App\Livewire\Users\TeamMembers;
use App\Livewire\Vendors\VendorCreate;

use Livewire\Component;
use App\Livewire\Dashboard\DashboardShow;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserCreate extends Component
{
    use AuthorizesRequests;

    public UserForm $form;

    public $view_text = [
        'card_title' => 'Create User',
        'button_text' => 'Add User',
        'form_submit' => 'save',
    ];

    public $model = ['type' => NULL, 'id' => NULL];
    public $user_cell = FALSE;
    public $user_form = FALSE;

    // public $business_name = NULL;
    public $via_vendor = NULL;
    public $via_vendors = [];
    public $via_client = NULL;
    // public $client_user_form = NULL;

    // public $user_vendor_id = NULL;
    // public $user_clients = NULL;

    protected $listeners = ['refreshComponent' => '$refresh', 'newMember', 'removeMember', 'ViaVendorId'];

    public function rules()
    {
        return [
            'user_cell' => 'required|digits:10',
        ];
    }

    public function updated($field, $value)
    {
        if($field == 'user_cell'){
            $this->user_form = FALSE;
        }

        $this->validateOnly($field);
    }

    public function ViaVendorId($via_vendor_id)
    {
        $this->via_vendor = TRUE;
        $this->form->via_vendor = $via_vendor_id;
        //UPDATE $via_vendors in view here
        $this->via_vendors = $this->form->user->vendors()->where('business_type', '!=', 'Sub')->get();
        $this->render();
    }

    public function create_via_vendor()
    {
        //dispatch to VendorCreate with user, via_vendor (come back here after with via_vendor(id))
        $this->dispatch('viaVendor', user: $this->form->user, business_name: $this->form->business_name)->to(VendorCreate::class);
    }

    public function user_cell_find()
    {
        $this->form->reset();
        $this->via_vendor = FALSE;
        $this->validateOnly('user_cell');

        $user = User::where('cell_phone', $this->user_cell)->first();

        if($user){
            $this->form->setUser($user);
        }

        // $this->resetErrorBag();
        $this->user_form = TRUE;

        if($this->model['type'] == 'vendor'){
            if($this->model['id'] == 'NEW'){
                // $this->form->role = 1; //Admin
                // $this->form->hourly_rate = 0;
            }else{
                if(auth()->user()->vendor->id != $this->model['id']){
                    $this->form->role = 2; //Team Member //Admin
                    $this->form->hourly_rate = 0;
                }else{
                    if($user){
                        $this->via_vendor = TRUE;
                        $this->via_vendors = $this->form->user->vendors()->where('business_type', '!=', 'Sub')->get();
                        $this->form->business_name = $this->form->user->full_name;
                    }
                }
            }
            // $this->form->business_name = $this->form->user->full_name;
        }elseif($this->model['type'] == 'client'){
            if($this->model['id'] == 'NEW'){
                $this->via_client = TRUE;
                // $this->user->role = 1; //Admin
                // $this->user->hourly_rate = 0; //Admin
            }else{
                $this->via_client = FALSE;
            }

            // $this->user_clients = $user->clients()->withoutGlobalScopes()->get();

            // dd($this->user_clients);
            // $this->client_user_form = TRUE;
        }else{
            dd('in user_cell else');
            abort(404);
        }

        // $this->form->reset();
        $this->resetErrorBag();
        // $this->user_form = TRUE;
    }

    // public function via($model)
    // {
    //     if($model == 'vendor'){
    //         $this->user_vendors = $this->user->vendors()->whereIn('vendors.business_type', ['Sub', '1099', 'DBA'])->wherePivot('is_employed', 1)->get();
    //     }elseif($model == 'client'){
    //         $this->user_clients = $this->user->clients()->withoutGlobalScopes()->get();
    //     }
    // }

    //new Vendor or Client member
    public function newMember($model, $model_id = NULL)
    {
        $this->user_cell = FALSE;
        $this->user_form = NULL;

        //creating new Vendor or Client or adding Team Member/Client User to existing Vendor or Client
        $this->model['type'] = $model;
        $this->model['id'] = $model_id;

        // 5-17-2023 ... this creates duplicates in the array of $this->model
        if($model == 'client'){
            if($this->model['id'] == 'NEW'){
                $this->view_text['card_title'] = 'Create Client';
                $this->view_text['button_text'] = 'Continue to Client';
            }else{
                $this->view_text['card_title'] = 'Add User to Client';
                $this->view_text['button_text'] = 'Add User';
            }
        }elseif($model == 'vendor'){
            //if creating User for New Vendor dont show user_role or user_hourly
            if($this->model['id'] == 'NEW'){
                $this->view_text['card_title'] = 'Add Owner to Vendor';
                $this->view_text['button_text'] = 'Add Owner';
            }else{
                $this->view_text['card_title'] = 'Add User to Vendor';
                $this->view_text['button_text'] = 'Add User';
            }
        }

        $this->modal('user_form_modal')->show();
    }

    public function removeMember(User $user)
    {
        // 2-7-22 need REMOVAL MODAL to confirm
        $user->vendor->users()->wherePivot('is_employed', '1')
            ->updateExistingPivot($user->id, [
                'end_date' => today()->format('Y-m-d'),
                'is_employed' => 0,
                'updated_at' => now()
            ]);

        // Assuming you have User and Role models with a many-to-many relationship
        // Fetch all pivot entries for this user

        $this->redirect(DashboardShow::class, navigate: true);
        //6-1-2024 set blurry background...
        $this->dispatch('notify',
            type: 'success',
            content: $user->first_name . ' Removed.'
        );
    }

    // Everthing in top pulbic should be reset here

    function validateMultiple($fields) {
        $validated = [];
        foreach ($fields as $field) {
            $validatedData = $this->validateOnly($field);
            $validated[key($validatedData)] = current($validatedData);
        }

        return $validated;
    }

    // public function storeUserSolo()
    // {
    //     //validate locally here...
    //     $this->validateMultiple(['user.first_name', 'user.last_name', 'user.email']);

    //     if(!$this->user->id){
    //         $this->user = User::create([
    //             'first_name' => $this->user->first_name,
    //             'last_name' => $this->user->last_name,
    //             'cell_phone' => $this->user->cell_phone,
    //             'email' => $this->user->email
    //         ]);
    //     }

    //     $this->user_exists = TRUE;
    //     $this->via($this->model['type']);
    // }

    public function save_user_only()
    {
        $user = $this->form->store();
        $this->form->setUser($user);

        //if model is Vendor only
        if($this->model['type'] == 'vendor'){
            $this->form->business_name = $user->full_name;
            $this->via_vendor = TRUE;
        }
    }

    public function update()
    {
        dd('in update');
    }

    public function save()
    {
        if(isset($this->form->user)){
            $user = $this->form->user;
        }else{
            //create New User
            $user = $this->form->store();
        }

        //Vendor User
        if($this->model['type'] == 'vendor'){
            // when creating new Vendor
            if($this->model['id'] == 'NEW'){
                $user->hourly_rate = 0;
                $user->role = 1;

                $this->modal('user_form_modal')->close();
                $this->dispatch('userVendor', $user->toArray());
            }else{
                $vendor = Vendor::findOrFail($this->model['id']);
                if($vendor->users()->where('user_id', $user->id)->employed()->doesntExist()){
                    $user->vendors()->attach(
                        $this->model['id'], [
                            'role_id' => $this->form->role,
                            'hourly_rate' => $this->form->hourly_rate,
                            'start_date' => today()->format('Y-m-d'),
                            'via_vendor_id' => $this->form->via_vendor ?? NULL
                        ]
                    );

                    $this->modal('user_form_modal')->close();

                    $this->dispatch('confirmProcessStep', 'team_members')->to('entry.vendor-registration');
                    // $this->dispatch('fakeRefresh', vendor: $vendor->id)->to(TeamMembers::class);

                    $this->dispatch('notify',
                        type: 'success',
                        content: 'User Added to Vendor'
                    );
                }else{
                    $this->addError('user_exists_on_model', 'User already belongs to Vendor.');
                }
            }
        //Client User
        //if existing User .. dispatchTo ClientCreate with user (show existing users the User is part of) and close $this->modal.
        }elseif($this->model['type'] == 'client'){
            $this->dispatch('addUser', user: $user->id, client_id: $this->model['id'])->to(ClientCreate::class);
            $this->modal('user_form_modal')->close();
        }
    }

    public function render()
    {
        return view('livewire.users.form');
    }
}
