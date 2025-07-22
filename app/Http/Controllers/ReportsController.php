<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lgu;
use App\Models\Questionnaire;
use App\Models\AssessmentQuestionnaire;



class ReportsController extends Controller
{
    public function paramReport(Request $request)
    {
        $lgus = Lgu::select('id', 'name')->get();
         // Fetch all assessment data (filter if needed by lgu_id, period_id, etc.)
        $assessments = AssessmentQuestionnaire::with('questionnaireLevel')->get();


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

        return view('admin.reports.parameter', compact('sections', 'lgus', 'assessments'));
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


        return view('admin.reports.compliance', compact('lgus', 'trgfy', 'bcdg', 'ksuys', 'pitsv', 'dyeie', 'psisjs'));
    }







}
