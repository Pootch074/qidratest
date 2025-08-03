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
    public function paramReport(Request $request)
    {
        $periodId = $request->input('period_id');
        $lguId = $request->input('lgu_id');

        $lgus = collect();
        if ($periodId) {
            $lgus = Lgu::whereIn('id', function ($query) use ($periodId) {
                $query->select('lgu_id')
                    ->from('period_assessments')
                    ->where('period_id', $periodId);
            })->select('id', 'name', 'lgu_type')->get();
        }

        $cksu = Period::select('id', 'name')->get();

        // Fetch assessment date from period_assessments
        $assessment = DB::table('period_assessments')
            ->where('period_id', $periodId)
            ->where('lgu_id', $lguId)
            ->first();

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

        $weights = DB::table('questionnaire_weights')
            ->pluck('weight', 'questionnaire_id')
            ->toArray();

        $totalWeight = 0;
        $totalNewIndexScore = 0;

        foreach ($sections as &$section) {
            foreach ($section['children'] as &$child) {
                $weight = $weights[$child->id] ?? 0;
                $totalWeight += $weight;

                $grandchildren = $section['grandchild']->where('parent_id', $child->id);
                $levels = $grandchildren->map(fn($g) => $g->assessment?->questionnaireLevel?->level ?? 0);

                $averageLevel = $levels->avg() ?? 0;
                $newIndexScore = $averageLevel * $weight;

                $child->new_index_score = $newIndexScore;
                $totalNewIndexScore += $newIndexScore;
            }
        }
        unset($child, $section);

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
            'weights',
            'totalWeight',
            'totalNewIndexScore',
            'weightedLevelGroup1',
            'weightedLevelGroup2',
            'assessment' // pass this to Blade
        ));
    }



    public function complianceMonitoring(Request $request)
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

        $averageLevels = [];

        foreach ($sections as &$section) {
            $grandchildren = $section['grandchild'];

            foreach ($section['children'] as $child) {
                $childGrandchildren = $grandchildren->where('parent_id', $child->id);

                $averageLevels[$child->id] = $childGrandchildren
                    ->pluck('assessment.questionnaireLevel.level')
                    ->filter()
                    ->avg();
            }
        }

        $weights = DB::table('questionnaire_weights')
        ->pluck('weight', 'questionnaire_id')
        ->toArray();

        // dd($weights);
        $totalWeight = 0;
        $totalNewIndexScore = 0;

        foreach ($sections as &$section) {
            foreach ($section['children'] as &$child) {
                $weight = $weights[$child->id] ?? 0;
                $totalWeight += $weight;

                $grandchildren = $section['grandchild']->where('parent_id', $child->id);
                $levels = $grandchildren->map(fn($g) => $g->assessment?->questionnaireLevel?->level ?? 0);

                $averageLevel = $levels->avg() ?? 0;
                $newIndexScore = $averageLevel * $weight;

                $child->new_index_score = $newIndexScore;
                $totalNewIndexScore += $newIndexScore;
            }
        }

        unset($child, $section); // to avoid accidental reference bugs

        $level = '';

        if ($totalNewIndexScore >= 4.21) {
            $level = 'LEVEL 5';
        } elseif ($totalNewIndexScore >= 3.41) {
            $level = 'LEVEL 4';
        } elseif ($totalNewIndexScore >= 2.61) {
            $level = 'LEVEL 3';
        } elseif ($totalNewIndexScore >= 1.81) {
            $level = 'LEVEL 2';
        } elseif ($totalNewIndexScore >= 1.00) {
            $level = 'LEVEL 1';
        } else {
            $level = 'NOT RATED';
        }


        return view('admin.reports.compliance', compact(
            'sections',
            'averageLevels',
            'lgus',
            'cksu',
            'weights',
            'totalWeight',
            'totalNewIndexScore',
            'level'
        ));
    }
}
