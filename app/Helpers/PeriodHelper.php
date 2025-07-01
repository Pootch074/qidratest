<?php

namespace App\Helpers;

use App\Models\Lgu;
use App\Models\Period;
use App\Models\PeriodAssessment;
use App\Models\PeriodAssessor;

class PeriodHelper
{
    public static function currentPeriod()
    {
        return Period::where('status', 'ongoing')->first();
    }

    public static function currentPeriodId()
    {
        return optional(self::currentPeriod())->id;
    }

    public static function getLgus($userId, $single = false)
    {

        $assessors = PeriodAssessor::where('user_id', $userId)
            ->pluck('period_assessment_id');

        $currentPeriodId = PeriodHelper::currentPeriodId();

        $lguIdsRMT = PeriodAssessment::whereIn('id', $assessors)
            ->where('period_id', $currentPeriodId)
            ->pluck('lgu_id');

        $lguIdsTL = PeriodAssessment::where('user_id', $userId)
            ->where('period_id', $currentPeriodId)
            ->pluck('lgu_id');

        $lguIds = array_unique(array_merge(
            $lguIdsRMT->toArray(),
            $lguIdsTL->toArray()
        ));

        if ($single) {
            return Lgu::find($lguIds[0]);
        }

        return Lgu::whereIn('id', $lguIds)->get();
    }

}
