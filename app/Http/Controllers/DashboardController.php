<?php

namespace App\Http\Controllers;

use App\Helpers\DashboardHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\PeriodHelper;
use App\Models\AssessmentQuestionnaire;
use App\Models\PeriodAssessment;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {

        $period = PeriodHelper::currentPeriod();
        if (!$period) {
            return redirect()->route('period-management');
        }

        // pending assessment
        $pending = AssessmentQuestionnaire::where('period_id', $period->id)->where('user_id', auth()->user()->id)->count();
        // completed assessment
        $completed = AssessmentQuestionnaire::where('period_id', $period->id)->where('user_id', auth()->user()->id)->count();
        // total assessment
        $total = $pending + $completed;
        // extension request
        $extension = AssessmentQuestionnaire::where('period_id', $period->id)->where('user_id', auth()->user()->id)->count();

        // assigned assessments
        $assessments = PeriodAssessment::where('period_id', $period->id)->where('status', 'pending')->where('user_id', auth()->user()->id)->get();
        $events = $assessments->map(function ($assessment) {
            $hue = ($assessment->lgu_id * 47) % 360;
            $color = "hsl($hue, 70%, 60%)";

            // Use current date if end is missing
            $hasEndDate = $assessment->assessment_end_date !== null;
            $end = $hasEndDate
                ? date('Y-m-d', strtotime($assessment->assessment_end_date . ' +1 day'))
                : now()->format('Y-m-d');

            return [
                'title' => $assessment->lgu->name,
                'start' => $assessment->assessment_start_date,
                'end' => $end,
                'color' => $color, // ✅ green-500 for temp end date
                'borderColor' => $hasEndDate ? '#0a4a22ff' : null, // ✅ optional darker border
                'extendedProps' => [
                    'temporaryEnd' => !$hasEndDate,
                ],
            ];
        });

        $userType = DashboardHelper::currentView();

        return view(($userType . '/dashboard'), compact('pending', 'completed', 'total', 'extension', 'assessments', 'events'));
    }
}
