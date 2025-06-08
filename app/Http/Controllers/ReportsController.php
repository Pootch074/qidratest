<?php

namespace App\Http\Controllers;

use App\Models\MeansOfVerification;
use App\Models\Questionnaire;
use App\Models\QuestionnaireLevel;
use App\Models\QuestionnaireTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    // public function index()
    // {
    //     return view('admin.reports.index');
    // }

    public function paramReport()
    {
        return view('admin.reports.parameter');
    }

    public function complMonitor()
    {
        return view('admin.reports.compliance');
    }
}
