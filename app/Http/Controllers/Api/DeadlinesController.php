<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PeriodHelper;
use App\Http\Controllers\Controller;
use App\Models\PeriodAssessment;
use Illuminate\Http\Request;

class DeadlinesController extends Controller
{
    //
    public function get($userId, Request $request)
    {
        $periodId = PeriodHelper::currentPeriodId();
        $lguIds = PeriodHelper::getLgus($userId)->pluck('id')->toArray();

        $periodAssessments = PeriodAssessment::with('lgu')
            ->where('period_id', $periodId)
            ->whereIn('lgu_id', $lguIds)
            ->get();

        $formatted = $periodAssessments->map(function ($item) {
            return [
                'id' => $item->id,
                'lgu_name' => $item->lgu->name ?? 'N/A',
                'start_date' => $item->start_date?->format('Y-m-d'),
                'end_date' => $item->end_date?->format('Y-m-d'),
                'status' => $item->status ?? 'Pending',
            ];
        });

        return response()->json($formatted);
    }
}
