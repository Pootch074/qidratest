<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lgu;
use App\Models\Questionnaire;

class ReportsController extends Controller
{
    public function paramReport(Request $request)
    {
        $sdfty = Questionnaire::find(1);
        $mcyla = Questionnaire::where('parent_id', 1)->get();
        $xvcnm = Questionnaire::where('parent_id', 4)->get();

        $dsdsaa = Questionnaire::find(2);
        $errtt = Questionnaire::where('parent_id', 2)->get();

        $skdud = Questionnaire::find(3);
        $nchusus = Questionnaire::where('parent_id', 3)->get();

        return view('admin.reports.parameter', compact('sdfty', 'mcyla', 'dsdsaa', 'errtt', 'skdud', 'nchusus', 'xvcnm'));
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
