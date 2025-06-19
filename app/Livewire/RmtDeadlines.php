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

        $assessors = PeriodAssessor::where('user_id', $userId)
            ->pluck('period_assessment_id');

        $currentPeriodId = PeriodHelper::currentPeriodId();

        $lguIdsRMT = PeriodAssessment::whereIn('id', $assessors)
            ->where('period_id', $currentPeriodId)
            ->pluck('lgu_id');

        $lguIdsTL = PeriodAssessment::where('user_id', $userId)
            ->where('period_id', $currentPeriodId)
            ->pluck('lgu_id');

        $lguIds = array_unique(array_merge(
            $lguIdsRMT->toArray(),
            $lguIdsTL->toArray()
        ));

        $this->lgus = Lgu::whereIn('id', $lguIds)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.rmt-deadlines');
    }
}
