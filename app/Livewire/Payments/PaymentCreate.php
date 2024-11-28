<?php

namespace App\Livewire\Payments;

use App\Models\Project;
use App\Models\Client;
use App\Models\Payment;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

use App\Livewire\Forms\PaymentForm;

use Carbon\Carbon;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentCreate extends Component
{
    use AuthorizesRequests;

    public PaymentForm $form;

    public Client $client;
    public $client_id = NULL;
    public $projects = [];

    public $view = FALSE;

    protected $listeners = ['addProject', 'removeProject'];

    protected function rules()
    {
        return [
            'client_id' => 'nullable',
            'projects.*.amount' => 'required|numeric|regex:/^-?\d+(\.\d{1,2})?$/',
        ];
    }

    public function mount()
    {
        $this->authorize('create', Payment::class);
        $this->form->date = today()->format('Y-m-d');
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function updatedClientId(Client $client)
    {
        $this->client = $client;
        $this->projects = $client->projects()->orderBy('created_at', 'DESC')->get();
    }

    #[Computed]
    public function clients()
    {
        $YTD = Carbon::now()->subYear();
        //where has at least one project in the past year
        // 11/24/2024

        // $projects = Project::with('client')->with(['last_status' => function ($query) {
        //     $query->where('title', 'Active');
        // }])->where('created_at', '>=', $YTD)
        // ->orderBy('created_at', 'DESC')
        // ->get();

        // $projects = Project::with('client')
        // ->when(1 == 1, function($query) {
        //     return $query->status('Active')->sortByDesc('last_status.start_date');
        // })
        // ->get();

        // dd($projects);
        // return Client::whereHas('projects', function ($query) use ($YTD) {
        //     $query->where('projects.created_at', '>=', $YTD);
        // })
        // ->orderBy('created_at', 'DESC')
        // ->get();

        //->where('projects.created_at', '>=', $YTD);
        // $clients = Client::whereHas('projects', function ($query) use ($YTD) {
        //     $query->with(['status' => function ($query) {
        //             $query->where('title', 'Active');
        //         }]);
        // })->get();

        // $clients = Client::withWhereHas('projects', function ($query) use ($YTD) {
        //     $query->withWhereHas('last_status', function ($query) {
        //         return $query->where('title', '=', 'Active');
        //     })->orderBy('projects.created_at', 'DESC');
        // })
        // // ->orderBy('created_at', 'DESC')
        // ->get();

        // $projects = Project::with(['statuses' => function ($query){
        //     $query->where('title', 'Active')
        //           ->orderBy('created_at', 'desc')
        //           ->take(1);
        // }])->first();
        // //status(['Active', 'Service Call'])
        // // $projects = Project::with('client')->where('created_at', '>=', $YTD)->status(['Active', 'Service Call', 'Complete']);
        // //     ->withWhereHas('last_status', function ($query) {
        // //         return $query->where('title', '=', 'Active');
        // //     })->orderBy('created_at', 'DESC')->first();

        // dd($projects);

        // return $clients;
        return Client::withWhereHas('projects', function ($query) use ($YTD) {
            $query->where('projects.created_at', '>=', $YTD);
        })
        ->orderBy('created_at', 'DESC')
        ->get();
    }

    public function getClientPaymentSumProperty()
    {
        return collect($this->projects)->where('amount', '!=', NULL)->sum('amount');
    }

    // 8-31-2022 | 9-10-2023 similar on VendorPaymentForm
    public function addProject(Client $client = NULL)
    {
        if(isset($client->id)){
            $this->view = TRUE;
            $this->client_id = $client->id;
            $this->updatedClientId($client);
        }else{
            $this->client_id = NULL;
        }

        $this->modal('payment_form_modal')->show();
    }

    // public function removeProject($project_id_to_remove)
    // {
    //     $project = $this->projects->where('id', $project_id_to_remove)->first();
    //     $project->show = false;
    //     $project->amount = 0;

    //     $this->form->project_id = "";
    // }

    public function save()
    {
        // dd($this);
        //validate payment total is greater than $0
        //if less than or equal to 0... send back with error
        if($this->getClientPaymentSumProperty() === 0){
            return $this->addError('payment_total_min', 'Payment total needs to include at least 1 project and not equal $0.00');
        }else{
            $payment = $this->form->store();
        }

        return redirect()->route('projects.show', $payment->project_id);
    }

    #[Title('Payment')]
    public function render()
    {
        $view_text = [
            'card_title' => 'Create Client Payment',
            'button_text' => 'Add Payment',
            'form_submit' => 'save',
        ];

        return view('livewire.payments.form', [
            'view_text' => $view_text,
        ]);
    }
}
