<?php

namespace App\Livewire;

use App\Helpers\PeriodHelper;
use App\Models\Lgu;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeriodAssessment;
use App\Models\PeriodAssessor;
use App\Models\User;

class AdminAssignments extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'lgus.name';
    public $sortDirection = 'asc';

    public $teamLeaders = [];
    public $rmts = [];

    public function mount()
    {
        $this->teamLeaders = User::teamLeaders()->active()->get();
        $this->rmts = User::rmts()->active()->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $assignments = PeriodAssessment::query()
            ->leftJoin('lgus', 'period_assessments.lgu_id', '=', 'lgus.id')
            ->leftJoin('users', 'period_assessments.user_id', '=', 'users.id')
            ->select(
                'period_assessments.id',
                'lgus.name as lgu_name',
                'users.first_name as rmt_first_name',
                'users.last_name as rmt_last_name',
                'period_assessments.status'
            )
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('lgus.name', 'like', '%' . $this->search . '%')
                        ->orWhere('users.first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('users.last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->where('period_id', PeriodHelper::currentPeriodId())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        if ($assignments->isEmpty()) {
            // if there are no data, let's repopulate
            $periodId = PeriodHelper::currentPeriodId();
            $assessments = Lgu::pluck('id')->map(function ($lguId) use ($periodId) {
                return [
                    'period_id' => $periodId,
                    'lgu_id'    => $lguId,
                    'status'    => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            PeriodAssessment::insert($assessments);

            // Re-fetch assignments after insert
            $assignments = PeriodAssessment::query()
                ->leftJoin('lgus', 'period_assessments.lgu_id', '=', 'lgus.id')
                ->leftJoin('users', 'period_assessments.user_id', '=', 'users.id')
                ->select(
                    'period_assessments.id',
                    'lgus.name as lgu_name',
                    'users.first_name as rmt_first_name',
                    'users.last_name as rmt_last_name',
                    'period_assessments.status'
                )
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('lgus.name', 'like', '%' . $this->search . '%')
                            ->orWhere('users.first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('users.last_name', 'like', '%' . $this->search . '%');
                    });
                })
                ->where('period_id', $periodId)
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10);
        }



        return view('livewire.admin-assignments', [
            'assignments' => $assignments
        ]);
    }

    private function getAssignments()
    {
        return PeriodAssessment::query()
            ->leftJoin('lgus', 'period_assessments.lgu_id', '=', 'lgus.id')
            ->leftJoin('users', 'period_assessments.user_id', '=', 'users.id')
            ->select(
                'period_assessments.id',
                'lgus.name as lgu_name',
                'users.first_name as rmt_first_name',
                'users.last_name as rmt_last_name',
                'period_assessments.status'
            )
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('lgus.name', 'like', '%' . $this->search . '%')
                        ->orWhere('users.first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('users.last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->where('period_id', PeriodHelper::currentPeriodId())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }
    private function populatePeriodAssessments()
    {
        $periodId = PeriodHelper::currentPeriodId();

        $assessments = Lgu::pluck('id')->map(function ($lguId) use ($periodId) {
            return [
                'period_id' => $periodId,
                'lgu_id'    => $lguId,
                'status'    => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        PeriodAssessment::insert($assessments);
    }

    public function getAssesstors($periodAssessmentId)
    {
        $assessors = PeriodAssessor::with('user')
            ->where('period_assessment_id', $periodAssessmentId)
            ->get();

        $names = $assessors->map(function ($assessor) {
            return $assessor->user->first_name . ' ' . $assessor->user->last_name;
        })->implode('<br>');

        $userIds = $assessors->pluck('user_id')->implode(',');

        return '
            <div>' . $names . '</div>
            <input type="hidden" name="user_ids" value="' . $userIds . '">
        ';
    }
}
