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

    /**
     * Manages the assessment process by handling questionnaire navigation, 
     * LGU selection, and session updates. Retrieves the current period, 
     * questionnaire tree, and relevant assessments. Prepares data for the 
     * assessment view including roots, current questionnaire, parent, child, 
     * and related levels and means of verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */

    public function management(Request $request)
    {

        // Prerequisite: Get current period
        $currentPeriod = PeriodHelper::currentPeriod();
        $periodId = $currentPeriod->id;

        // Preparation: Get questionnaire tree from the period
        $questionnaireTreeId = session('questionnaireTreeId') ?? $currentPeriod->questionnaire_tree_id;

        // Preparation: if lgu is selected from dropdown, update session for lgu_id
        if (isset($request->lgu_id)) {
            session(['lguId' => $request->lgu_id]);

            return redirect('assessment-management');
        }
        $lguId = session('lguId') ?? PeriodHelper::getLgus(auth()->user()->id, true)->id;

        // Preparation: if parent questionnaire is clicked, update session for root_id
        if (isset($request->root_id)) {

            session(['rootId' => $request->root_id]);

            // for this change, we need to update childId and parentId based on rootId
            $parent = $this->getFirstQuestionnaire(session('questionnaireTreeId'), $request->root_id);
            $child = $this->getFirstChild($parent);

            session(['childId' => $child->id]);
            session(['parentId' => $parent->id]);

            return redirect('assessment-management');
        }
        $rootId = session('rootId');
        if (!$rootId) {
            // set again if rootId does not exist
            $child = $this->getFirstQuestionnaire($questionnaireTreeId);
            $currentRoot = $this->getCurrentRootQuestionnaire($child);
            $rootId = $currentRoot->id;
        }
        $currentRoot = Questionnaire::find($rootId);

        $childId = session('childId');
        $parentId = session('parentId');
        // Preparation: fallback for blank childId and parentId
        if (!$childId || !$parentId) {
            $child = $this->getFirstQuestionnaire($questionnaireTreeId);
            $childId = $child->id;
            $parentId = $child->parent_id;
        }

        // Preparation: if reference is clicked, update the current childId
        if (isset($request->ref)) {
            session(['childId' => $request->ref]);

            // make sure we have the correct parentId
            $child = $this->getSingleQuestionnaire($request->ref);
            session(['parentId' => $child->parent_id]);

            return redirect('assessment-management');
        }

        // Assemble: set session for all variables
        session([
            'rootId' => $rootId,
            'questionnaireTreeId' => $questionnaireTreeId,
            'lguId' => $lguId,
            'parentId' => $parentId,
            'childId' => $childId,
        ]);

        // Process: get questionnaire id from current period
        $questionnaire = QuestionnaireTree::find($questionnaireTreeId);
        $child = $this->getSingleQuestionnaire($childId);
        $parent = $this->getSingleQuestionnaire($parentId);
        $roots = $this->getRootQuestionnaires($questionnaireTreeId);
        $references = $this->getNavigation($questionnaireTreeId, $rootId, $lguId, $periodId);

        // Process: get movs
        $means = MeansOfVerification::where('questionnaire_id', $childId)->get();
        // Process: get levels
        $levels = QuestionnaireLevel::where('questionnaire_id', $childId)->get();
        // dd($levels);

        $userId = auth()->user()->id;
        $lguIds = PeriodHelper::getLgus($userId)->pluck('id')->toArray();
        $lgus = Lgu::whereIn('id', $lguIds)->get()->toArray();

        $assessment = AssessmentQuestionnaire::where('period_id', $periodId)
            ->where('lgu_id', $lguId)
            ->where('questionnaire_id', $childId)
            ->first();

        $selectedLevelId = $assessment->questionnaire_level_id ?? 0;
        $existingRemarks = $assessment->remarks ?? '';
        $existingRecommendations = $assessment->recommendations ?? '';

        $questionnaireId = $childId;
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

    private function getNavigation($treeId, $rootId, $lguId, $periodId)
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

    private function getFirstQuestionnaire($id, $rootId = 0)
    {
        $q = Questionnaire::where('questionnaire_tree_id', $id)
            ->when($rootId !== 0, function ($query) use ($rootId) {
                return $query->where('parent_id', $rootId);
            }, function ($query) {
                return $query->where('parent_id', '!=', 0)
                        ->where('reference_number', '!=', '');
            })
            ->orderBy('parent_id')
            ->orderBy('weight')
            ->first();
        
        return $q;
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

    private function getFirstChild($parent)
    {
        return Questionnaire::where('parent_id', $parent->id)->orderBy('id')->first();
    }
}
