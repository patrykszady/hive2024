<?php

namespace App\Livewire\Sheets;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SheetsIndex extends Component
{
    use AuthorizesRequests;

    public function mount()
    {

    }

    #[Title('Sheets')]
    public function render()
    {
        $this->authorize('viewAny', Sheet::class);

        return view('livewire.sheets.index');
    }
}
