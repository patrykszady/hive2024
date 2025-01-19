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
    public Payment $payment;

    public $client = NULL;
    public $client_id = NULL;
    public $projects = [];

    public $view = FALSE;

    public $view_text = [
        'card_title' => 'Create Client Payment',
        'button_text' => 'Add Payment',
        'form_submit' => 'save',
    ];

    protected $listeners = ['addProject', 'removeProject', 'editPayment'];

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
        // $YTD = Carbon::now()->subYear();
        //->where('projects.created_at', '>=', $YTD)
        $this->projects = $client->projects()->orderBy('projects.created_at', 'DESC')->status(['Active', 'Complete', 'Service Call', 'Service Call Complete']);
    }

    public function editPayment(Payment $payment)
    {
        $this->payment = $payment;
        $this->client = $payment->project->client;
        $this->client_id = $payment->project->client->id;
        $this->updatedClientId($this->client);
        $this->form->setPayment($this->payment);

        // dd($this->projects);
        $this->view_text = [
            'card_title' => 'Update Client Payment',
            'button_text' => 'Update Payment',
            'form_submit' => 'update',
        ];

        $this->modal('payment_form_modal')->show();
    }

    #[Computed]
    public function clients()
    {
        // $YTD = Carbon::now()->subYear();

        // use ($YTD)
        return Client::withWhereHas('projects', function ($query) {
            //->where('projects.created_at', '>=', $YTD)
            $query->whereHas('statuses', function ($query) {
                    return $query->where('title', '=', 'Active');
                });
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
        $this->view_text = [
            'card_title' => 'Create Client Payment',
            'button_text' => 'Add Payment',
            'form_submit' => 'save',
        ];

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
        return view('livewire.payments.form');
    }
}
