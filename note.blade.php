<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lgu;
use App\Models\Questionnaire;
use App\Models\AssessmentQuestionnaire;
use App\Models\QuestionnaireLevel;




class ReportsController extends Controller
{
    // Define a public method named 'paramReport' which accepts an HTTP request
    public function paramReport(Request $request)
    {
        // Fetch all LGUs (Local Government Units), selecting only their 'id' and 'name'
        $lgus = Lgu::select('id', 'name')->get();

        // Get the selected LGU ID from the request input (e.g., from a form or query parameter)
        $lguId = $request->input('lgu_id'); // Safe version

        // Fetch assessment questionnaires, including their related questionnaire level
        $assessments = Questionnaire::whereIn('id')->with('questionnaireLevel')->get();

        // Prepare an array of questionnaire sections with parents, children, and grandchildren
        $sections = [
            [
                // Find the parent questionnaire with ID 1
                'parent' => Questionnaire::find(1),
                // Get all child questionnaires whose parent_id is 1
                'children' => Questionnaire::where('parent_id', 1)->get(),
                // Get all grandchild questionnaires under parent IDs 4, 5, 6, and 7
                'grandchild' => Questionnaire::whereIn('parent_id', [4, 5, 6, 7])->get()
            ],
            [
                // Find the parent questionnaire with ID 2
                'parent' => Questionnaire::find(2),
                // Get all child questionnaires whose parent_id is 2
                'children' => Questionnaire::where('parent_id', 2)->get(),
                // Get all grandchild questionnaires under parent IDs 8–13
                'grandchild' => Questionnaire::whereIn('parent_id', [8, 9, 10, 11, 12, 13])->get()
            ],
            [
                // Find the parent questionnaire with ID 3
                'parent' => Questionnaire::find(3),
                // Get all child questionnaires whose parent_id is 3
                'children' => Questionnaire::where('parent_id', 3)->get(),
                // Get all grandchild questionnaires under parent IDs 14–17
                'grandchild' => Questionnaire::whereIn('parent_id', [14, 15, 16, 17])->get()
            ]
        ];

        // 👉 Define a closure function to calculate the average level score for a group of questionnaire IDs
        $calculateAvgLevel = function ($ids) use ($assessments) {
            // Get all 'questionnaire_level_id' values for the given questionnaire IDs
            $levelIds = $assessments->whereIn('questionnaire_id', $ids)->pluck('questionnaire_level_id');
            // Use those level IDs to get the corresponding level values and compute their average
            return QuestionnaireLevel::whereIn('id', $levelIds)->pluck('level')->avg();
        };

        // Calculate the average level for group 1 (questionnaires 18, 19, 20)
        $avgLevelGroup1 = $calculateAvgLevel([18, 19, 20]);
        // Multiply the average by a weight of 0.07 to get the weighted score for group 1
        $weightedLevelGroup1 = $avgLevelGroup1 * 0.07;

        // Calculate the average level for group 2 (questionnaires 21 to 30)
        $avgLevelGroup2 = $calculateAvgLevel(range(21, 30));
        // Multiply the average by a weight of 0.11 to get the weighted score for group 2
        $weightedLevelGroup2 = $avgLevelGroup2 * 0.11;

        // Return the Blade view 'parameter' inside 'admin/reports', passing all necessary variables to it
        return view('admin.reports.parameter', compact(
            'sections',
            'lgus',
            'assessments',
            'weightedLevelGroup1',
            'weightedLevelGroup2'
        ));
    }
}
