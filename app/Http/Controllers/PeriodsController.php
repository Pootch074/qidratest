<?php

namespace App\Http\Controllers;

use App\Helpers\PeriodHelper;
use App\Models\Lgu;
use App\Models\MeansOfVerification;
use App\Models\Period;
use App\Models\PeriodAssessment;
use App\Models\Questionnaire;
use App\Models\QuestionnaireLevel;
use App\Models\QuestionnaireTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodsController extends Controller
{
    //
    public function index()
    {
        $periods = Period::all();
        $questionnaireTrees = QuestionnaireTree::where('status', 'published')->get();
        return view('admin.periods.index', compact('periods', 'questionnaireTrees'));
    }

    public function assessments(Request $request)
    {
        // Get current active period or latest inactive
        $assessments = $this->getPeriodAssessments();
        return view('admin.periods.assessments', compact('assessments'));
    }

    public function assignments(Request $request)
    {
        return view('admin.periods.assignments');
    }

    public function switchPeriod($id)
    {
        $periods = Period::all();
        foreach ($periods as $period) {
            if ($period->id == $id) {
                $period->status = 'ongoing';
            }
            else {
                $period->status = 'completed';
            }
            $period->save();
        }

        return redirect()->back();

    }

    private function getPeriodAssessments(?Period $period = null)
    {
        if (is_null($period)) {
            $period = Period::orderByRaw("
            CASE
                WHEN status = 'ongoing' THEN 0
                WHEN status = 'inactive' THEN 1
                ELSE 2
            END
        ")
                ->latest('id')
                ->with(['assessments.lgu', 'assessments.user'])
                ->first();
        }

        // If there are no assessments, create them
        if ($period->assessments->isEmpty()) {
            $now = now();

            $lgus = Lgu::select('id')->get();

            $assessmentData = $lgus->map(fn($lgu) => [
                'lgu_id'     => $lgu->id,
                'status'     => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            $period->loadMissing('assessments'); // ensure relation is loaded before modifying
            $assessments = $period->assessments()->createMany($assessmentData);

            // Attach the newly created ones for further use
            $period->setRelation('assessments', collect($assessments));
        } else {
            $assessments = $period->assessments;
        }

        return $assessments;
    }
}
