<?php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lgu;
use App\Models\Period;
use App\Models\Questionnaire;
use App\Models\AssessmentQuestionnaire;
use App\Models\QuestionnaireLevel;
use App\Models\PeriodAssessment;
use Illuminate\Support\Facades\DB;


class ReportsController extends Controller
{
    // Define a public method named 'paramReport' which accepts an HTTP request
    public function paramReport(Request $request)
    {
        $periodId = $request->input('period_id');

        // Fetch only LGUs that have a record in period_assessments for the selected period
        $lgus = collect();

        if ($periodId) {
            $lgus = Lgu::whereIn('id', function ($query) use ($periodId) {
                $query->select('lgu_id')
                    ->from('period_assessments')
                    ->where('period_id', $periodId);
            })->select('id', 'name', 'lgu_type')->get();
        }

        $cksu = Period::select('id', 'name')->get();
        $lguId = $request->input('lgu_id');

        $sections = [
            [
                'parent' => Questionnaire::find(1),
                'children' => Questionnaire::where('parent_id', 1)->get(),
                'grandchild' => Questionnaire::with(['assessment' => function ($q) use ($lguId) {
                    $q->where('lgu_id', $lguId);
                }, 'assessment.level'])->whereIn('parent_id', [4, 5, 6, 7])->get()
            ],
            [
                'parent' => Questionnaire::find(2),
                'children' => Questionnaire::where('parent_id', 2)->get(),
                'grandchild' => Questionnaire::with(['assessment' => function ($q) use ($lguId) {
                    $q->where('lgu_id', $lguId);
                }, 'assessment.level'])->whereIn('parent_id', [8, 9, 10, 11, 12, 13])->get()
            ],
            [
                'parent' => Questionnaire::find(3),
                'children' => Questionnaire::where('parent_id', 3)->get(),
                'grandchild' => Questionnaire::with(['assessment' => function ($q) use ($lguId) {
                    $q->where('lgu_id', $lguId);
                }, 'assessment.level'])->whereIn('parent_id', [14, 15, 16, 17])->get()
            ]
        ];


        $calculateAvgWeight = function ($ids) {
            return Questionnaire::whereIn('id', $ids)->pluck('weight')->avg();
        };

        $avgLevelGroup1 = $calculateAvgWeight([18, 19, 20]);
        $weightedLevelGroup1 = $avgLevelGroup1 * 0.07;

        $avgLevelGroup2 = $calculateAvgWeight(range(21, 30));
        $weightedLevelGroup2 = $avgLevelGroup2 * 0.11;


        
        
        return view('admin.reports.parameter', compact(
            'sections',
            'lgus',
            'cksu',
            'weightedLevelGroup1',
            'weightedLevelGroup2',
        ));

        
    }
}
