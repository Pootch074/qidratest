<?php

namespace App\Helpers;

use App\Models\AssessmentMean;
use App\Models\AssessmentQuestionnaire;
use App\Models\Lgu;
use App\Models\Period;
use App\Models\PeriodAssessment;
use App\Models\PeriodAssessor;
use App\Models\Questionnaire;

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



    public function getNavigation($treeId, $rootId, $lguId, $periodId)
    {
        $ids = [];

        $collectDescendants = function ($parentId) use (&$collectDescendants, &$ids, $treeId) {
            $children = Questionnaire::where('questionnaire_tree_id', $treeId)
                ->where('parent_id', $parentId)
                ->pluck('id');

            foreach ($children as $childId) {
                $ids[] = $childId;
                $collectDescendants($childId);
            }
        };

        $ids[] = $rootId;
        $collectDescendants($rootId);

        $questionnaires = Questionnaire::where('questionnaire_tree_id', $treeId)
            ->whereIn('id', $ids)
            ->select('id', 'parent_id', 'reference_number')
            ->where('reference_number', '!=', '')
            ->orderBy('parent_id')
            ->orderBy('weight')
            ->get();

        $questionnaireArray = [];

        foreach ($questionnaires as $q) {
            $existCount = 0;
            // check if questionnaire_id exists in assessment_means
            if (AssessmentMean::where('questionnaire_id', $q->id)
                ->where('period_id', $periodId)
                ->where('lgu_id', $lguId)->exists()) {
                $existCount++;
            }
            // check if questionnaire_id has questionnaire_level in assessment_questionnaires
            if (AssessmentQuestionnaire::where('questionnaire_id', $q->id)
                ->where('questionnaire_level_id', '!=', '')
                ->where('period_id', $periodId)
                ->where('lgu_id', $lguId)->exists()) {
                $existCount++;
            }
            
            // check if questionnaire_id has remarks in assessment_questionnaires
            if (AssessmentQuestionnaire::where('questionnaire_id', $q->id)
                ->where('period_id', $periodId)
                ->where('lgu_id', $lguId)
                ->where('remarks', '!=', '')->exists()) {
                $existCount++;
            }
            // check if questionnaire_id has recommendations in assessment_questionnaires
            if (AssessmentQuestionnaire::where('questionnaire_id', $q->id)
                ->where('period_id', $periodId)
                ->where('lgu_id', $lguId)
                ->where('recommendations', '!=', '')->exists()) {
                $existCount++;
            }

            $status = 'pending';
            if ($existCount > 0 && $existCount < 4) {
                $status = 'inprogress';
            }
            if ($existCount > 3) {
                $status = 'completed';
            }
            
            $questionnaireArray[$q->id] = [
                'id' => $q->id,
                'parent_id' => $q->parent_id,
                'reference_number' => $q->reference_number,
                'status' => $status
            ];
        }

        return $questionnaireArray;
    }

    /**
     * Recursively traverse the questionnaire tree to get the root of the current assessment.
     * 
     * @param Questionnaire $child
     * @return Questionnaire
     */
    public function getRootQuestionnaires($id)
    {
        return Questionnaire::where('questionnaire_tree_id', $id)->where('parent_id', 0)->get();
    }

}
