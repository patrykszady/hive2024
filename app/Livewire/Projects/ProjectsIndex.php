<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\Client;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectsIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    public $project_name_search = '';
    public $client_id = '';
    public $client = NULL;
    public $project_status_title = 'Active';
    public $view;

    protected $queryString = [
        'project_name_search' => ['except' => ''],
        'client_id' => ['except' => ''],
        'project_status_title' => ['except' => '']
    ];

    public function mount()
    {
        if($this->client){
            $this->client_id = $this->client->id;
        }

        if($this->view == TRUE){
            $this->project_status_title = NULL;
        }
    }

    public function updating($field)
    {
        $this->resetPage();
    }

    #[Title('Projects')]
    public function render()
    {
        $this->authorize('viewAny', Project::class);

        if(!is_null($this->client)){
            if(isset($this->client->vendor_id)){
                //all clients(projects) with $client->vendor_id
                $client_ids = Project::where('belongs_to_vendor_id', $this->client->vendor_id)->pluck('client_id')->toArray();
            }else{
                $client_ids = [$this->client->id];
            }
        }else{
            $client_ids = [];
        }

        // dd($this->client ? $this->client : NULL);

        $clients = Client::orderBy('created_at', 'DESC')->get();

        $projects = Project::orderBy('created_at', 'DESC')
            ->withWhereHas('vendors', function ($query) {
                $query->where('vendor_id', auth()->user()->vendor->id);
            })
            ->where('address', 'like', "%{$this->project_name_search}%")
            ->when($this->project_status_title != NULL, function($query) {
                return $query->status($this->project_status_title)->sortByDesc('last_status.start_date');
            })
            ->when($this->client != NULL, function ($query) use ($client_ids) {
                return $query->whereIn('client_id', $client_ids);
            })

            ->paginate(10);

        return view('livewire.projects.index', [
            'projects' => $projects,
            'clients' => $clients,
        ]);
    }
}
