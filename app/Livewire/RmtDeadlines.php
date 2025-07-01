<?php

namespace App\Livewire;

use App\Helpers\PeriodHelper;
use App\Models\Lgu;
use App\Models\Period;
use App\Models\PeriodAssessment;
use App\Models\PeriodAssessor;
use App\Models\User;
use Livewire\Component;

class RmtDeadlines extends Component
{
    public array $lgus = [];
    public int|null $selectedLguId = null;

    public function mount()
    {
        $userId = auth()->user()->id;
        $lguIds = PeriodHelper::getLgus($userId)->pluck('id')->toArray();

        $this->lgus = Lgu::whereIn('id', $lguIds)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.rmt-deadlines');
    }
}
