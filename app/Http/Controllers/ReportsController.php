<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lgu;
use App\Models\Questionnaire;
use App\Models\AssessmentQuestionnaire;
use App\Models\QuestionnaireLevel;




class ReportsController extends Controller
{
    public function paramReport(Request $request)
    {
        $lgus = Lgu::select('id', 'name')->get();
        $lguId = $request->input('lgu_id'); // Safe version

        $assessments = AssessmentQuestionnaire::with('questionnaireLevel')
            ->when($lguId, function ($query) use ($lguId) {
                $query->where('lgu_id', $lguId);
            })
            ->get();



        $sections = [
            [
                'parent' => Questionnaire::find(1),
                'children' => Questionnaire::where('parent_id', 1)->get(),
                'grandchild' => Questionnaire::whereIn('parent_id', [4, 5, 6, 7])->get()

            ],
            [
                'parent' => Questionnaire::find(2),
                'children' => Questionnaire::where('parent_id', 2)->get(),
                'grandchild' => Questionnaire::whereIn('parent_id', [8, 9, 10, 11, 12, 13])->get()

            ],
            [
                'parent' => Questionnaire::find(3),
                'children' => Questionnaire::where('parent_id', 3)->get(),
                'grandchild' => Questionnaire::whereIn('parent_id', [14, 15, 16, 17])->get()

            ]

        ];


        // 👉 Add weighted group scores
        $calculateAvgLevel = function ($ids) use ($assessments) {
            $levelIds = $assessments->whereIn('questionnaire_id', $ids)->pluck('questionnaire_level_id');
            return QuestionnaireLevel::whereIn('id', $levelIds)->pluck('level')->avg();
        };

        $avgLevelGroup1 = $calculateAvgLevel([18, 19, 20]);
        $weightedLevelGroup1 = $avgLevelGroup1 * 0.07;

        $avgLevelGroup2 = $calculateAvgLevel(range(21, 30));
        $weightedLevelGroup2 = $avgLevelGroup2 * 0.11;

        return view('admin.reports.parameter', compact(
            'sections',
            'lgus',
            'assessments',
            'weightedLevelGroup1',
            'weightedLevelGroup2'
        ));
    }


    public function complianceMonitoring(Request $request)
    {
        $lgus = Lgu::select('id', 'name')->get();

        $trgfy = Questionnaire::find(1);
        $bcdg = Questionnaire::where('parent_id', 1)->get();

        $ksuys = Questionnaire::find(2);
        $pitsv = Questionnaire::where('parent_id', 2)->get();

        $dyeie = Questionnaire::find(3);
        $psisjs = Questionnaire::where('parent_id', 3)->get();

        // Helper function to calculate average level
        $calculateAvgLevel = function ($ids) {
            $levelIds = AssessmentQuestionnaire::whereIn('questionnaire_id', $ids)
                ->pluck('questionnaire_level_id');
            return QuestionnaireLevel::whereIn('id', $levelIds)->pluck('level')->avg();
        };
        // ===== A. Administration and Organization =======
        $avgLevelGroup1 = $calculateAvgLevel([18, 19, 20]);
        $weightedLevelGroup1 = $avgLevelGroup1 * 0.07; // 7%
        $avgLevelGroup2 = $calculateAvgLevel(range(21, 30));
        $weightedLevelGroup2 = $avgLevelGroup2 * 0.11;
        $avgLevelGroup3 = $calculateAvgLevel(range(31, 35));
        $weightedLevelGroup3 = $avgLevelGroup3 * 0.09;
        $avgLevelGroup4 = $calculateAvgLevel(range(36, 41));
        $weightedLevelGroup4 = $avgLevelGroup4 * 0.08;

        // ===== B. Program Management =======
        $avgLevelGroup5 = $calculateAvgLevel(range(42, 44));
        $weightedLevelGroup5 = $avgLevelGroup5 * 0.16;
        $avgLevelGroup6 = $calculateAvgLevel(range(45, 51));
        $weightedLevelGroup6 = $avgLevelGroup6 * 0.045;
        $avgLevelGroup7 = $calculateAvgLevel(range(52, 58));
        $weightedLevelGroup7 = $avgLevelGroup7 * 0.045;
        $avgLevelGroup8 = $calculateAvgLevel(range(59, 61));
        $weightedLevelGroup8 = $avgLevelGroup8 * 0.07;
        $avgLevelGroup9 = $calculateAvgLevel(range(62, 67));
        $weightedLevelGroup9 = $avgLevelGroup9 * 0.13;
        $avgLevelGroup10 = $calculateAvgLevel(range(68, 69));
        $weightedLevelGroup10 = $avgLevelGroup10 * 0.8;

        // ===== C. Institutional Mechanism =======
        $avgLevelGroup11 = $calculateAvgLevel(range(70, 76));
        $weightedLevelGroup11 = $avgLevelGroup11 * 0.06;
        $avgLevelGroup12 = $calculateAvgLevel(range(77, 83));
        $weightedLevelGroup12 = $avgLevelGroup12 * 0.05;
        $avgLevelGroup13 = $calculateAvgLevel([84]);
        $weightedLevelGroup13 = $avgLevelGroup13 * 0.04;
        $avgLevelGroup14 = $calculateAvgLevel([85]);
        $weightedLevelGroup14 = $avgLevelGroup14 * 0.05;

        $totalWeightedScore = $weightedLevelGroup1 +
            $weightedLevelGroup2 +
            $weightedLevelGroup3 +
            $weightedLevelGroup4 +
            $weightedLevelGroup5 +
            $weightedLevelGroup6 +
            $weightedLevelGroup7 +
            $weightedLevelGroup8 +
            $weightedLevelGroup9 +
            $weightedLevelGroup10 +
            $weightedLevelGroup11 +
            $weightedLevelGroup12 +
            $weightedLevelGroup13 +
            $weightedLevelGroup14;



        return view('admin.reports.compliance', compact(
            'lgus',
            'trgfy',
            'bcdg',
            'ksuys',
            'pitsv',
            'dyeie',
            'psisjs',
            'weightedLevelGroup1',
            'weightedLevelGroup2',
            'weightedLevelGroup3',
            'weightedLevelGroup4',
            'weightedLevelGroup5',
            'weightedLevelGroup6',
            'weightedLevelGroup7',
            'weightedLevelGroup8',
            'weightedLevelGroup9',
            'weightedLevelGroup10',
            'weightedLevelGroup11',
            'weightedLevelGroup12',
            'weightedLevelGroup13',
            'weightedLevelGroup14',
            'totalWeightedScore'

        ));
    }
}
