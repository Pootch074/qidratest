<?php

namespace App\Livewire;

use App\Helpers\PeriodHelper;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeriodAssessment;

class AdminAssignments extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'lgus.name';
    public $sortDirection = 'asc';

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
            ->leftJoin('users', 'period_assessments.rmt_id', '=', 'users.id')
            ->select(
                'period_assessments.id',
                'lgus.name as lgu_name',
                'users.first_name as rmt_first_name',
                'users.last_name as rmt_last_name'
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

        return view('livewire.admin-assignments', [
            'assignments' => $assignments
        ]);
    }
}
