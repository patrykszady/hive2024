<?php

namespace App\Livewire\Vendors;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Vendor;

class VendorsIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    public $business_name = '';
    public $vendor_type = '';
    public $view;

    public $sortBy = 'business_name';
    public $sortDirection = 'asc';

    protected $queryString = [
        'business_name' => ['except' => ''],
        'vendor_type' => ['except' => '']
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function updating($field)
    {
        $this->resetPage();
    }

    #[Computed]
    public function vendors()
    {
        return Vendor::
            where('business_name', 'like', "%{$this->business_name}%")
            ->where('business_type', 'like', "%{$this->vendor_type}%")
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(10);
    }

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Title('Vendors')]
    public function render()
    {
        return view('livewire.vendors.index');
    }
}
