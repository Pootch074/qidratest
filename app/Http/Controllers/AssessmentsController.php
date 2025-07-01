<?php

namespace App\Http\Controllers;

use App\Helpers\PeriodHelper;
use App\Models\AssessmentMean;
use App\Models\AssessmentQuestionnaire;
use App\Models\Lgu;
use App\Models\MeansOfVerification;
use App\Models\Questionnaire;
use App\Models\QuestionnaireLevel;
use App\Models\QuestionnaireTree;
use Illuminate\Http\Request;

class AssessmentsController extends Controller
{
    //

    public function management()
    {

        $currentPeriod = PeriodHelper::currentPeriod();
        $periodId = $currentPeriod->id;
        $questionnaireId = session('questionnaireId') ?? $currentPeriod->questionnaire_id;

        $lguId = session('lguId') ?? PeriodHelper::getLgus(auth()->user()->id, true)->id;

        $childId = session('childId');
        $parentId = session('parentId');
        if (!$childId || !$parentId) {
            $child = $this->getFirstQuestionnaire($questionnaireId);
            $childId = $child->id;
            $parentId = $child->parent_id;
        }

        session([
            'questionnaireId' => $questionnaireId,
            'lguId' => $lguId,
            'parentId' => $parentId,
            'childId' => $childId,
        ]);

        // get questionnaire id from current period
        $questionnaire = QuestionnaireTree::find($questionnaireId);
        $child = $this->getSingleQuestionnaire($childId);
        $parent = $this->getSingleQuestionnaire($parentId);
        $roots = $this->getRootQuestionnaires($questionnaireId);
        $currentRoot = $this->getCurrentRootQuestionnaire($child);
        $references = $this->getNavigation($questionnaireId, $currentRoot->id);

        $means = MeansOfVerification::where('questionnaire_id', $child->id)->get();
        $levels = QuestionnaireLevel::where('questionnaire_id', $child->id)->get();

        $userId = auth()->user()->id;
        $lguIds = PeriodHelper::getLgus($userId)->pluck('id')->toArray();
        $lgus = Lgu::whereIn('id', $lguIds)->get()->toArray();

        $assessment = AssessmentQuestionnaire::where('period_id', $periodId)
            ->where('lgu_id', $lguId)
            ->where('questionnaire_id', $questionnaireId)
            ->first();

        $selectedLevelId = $assessment->questionnaire_level_id ?? 0;
        $existingRemarks = $assessment->remarks ?? '';
        $existingRecommendations = $assessment->recommendations ?? '';

        $checkedMeans = AssessmentMean::where('period_id', $periodId)
        ->where('lgu_id', $lguId)
        ->where('questionnaire_id', $questionnaireId)
        ->pluck('means_id')
        ->toArray();

        return view('rmt.assessments.view', compact(
            'roots', 'currentRoot', 'questionnaire', 
            'child', 'parent', 
            'periodId', 'questionnaireId', 'lguId', 
            'existingRemarks', 'existingRecommendations', 'selectedLevelId', 'checkedMeans',
            'references', 'means', 'levels', 'lgus'
        ));
    
    }

    private function getNavigation($id, $rootId)
    {
        return Questionnaire::where('questionnaire_tree_id', $id)
            ->select('id', 'parent_id', 'reference_number')
            ->where('reference_number', '!=', '')
            ->orderBy('parent_id')
            ->orderBy('weight')
            ->get()
            ->toArray();
    }
    private function getFirstQuestionnaire($id)
    {
        return Questionnaire::where('questionnaire_tree_id', $id)
            ->where('reference_number', '!=', '')
            ->orderBy('parent_id')
            ->orderBy('weight')
            ->first();
    }

    private function getSingleQuestionnaire($id)
    {
        return Questionnaire::find($id);
    }

    /**
     * Recursively traverse the questionnaire tree to get the root of the current assessment.
     * 
     * @param Questionnaire $child
     * @return Questionnaire
     */
    private function getRootQuestionnaires($id)
    {
        return Questionnaire::where('questionnaire_tree_id', $id)->where('parent_id', 0)->get();
    }

    /**
     * Recursively traverse the questionnaire tree to get the root of the current assessment.
     *
     * @param int $childId
     * @return Questionnaire
     */

    private function getCurrentRootQuestionnaire($child)
    {
        if ($child->parent_id == 0) {
            return $child;
        }

        $parent = $child->parent()->first();
        return $this->getCurrentRootQuestionnaire($parent);
    }
}
