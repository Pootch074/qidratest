<?php

namespace App\Http\Controllers;

use App\Helpers\PeriodHelper;
use App\Models\AssessmentQuestionnaire;
use App\Models\MeansOfVerification;
use App\Models\PeriodAssessment;
use App\Models\Questionnaire;
use App\Models\QuestionnaireLevel;
use App\Models\QuestionnaireTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionnairesController extends Controller
{
    //
    public function index()
    {
        $questionnaires = QuestionnaireTree::all();
        return view('admin.questionnaires.index', compact('questionnaires'));
    }

    public function manageQuestionnaires($questionnaireTreeId)
    {
        $periodHelper = new PeriodHelper();

        $questionnaire = QuestionnaireTree::find($questionnaireTreeId);
        $child = $this->getFirstQuestionnaire($questionnaireTreeId);
        $parent = $child->parent;

        $roots = $periodHelper->getRootQuestionnaires($questionnaireTreeId);
        $currentRoot = $roots[0];

        $questionnaireId = $child->id;
        $periodId = PeriodHelper::currentPeriodId();
        $lguId = PeriodAssessment::where('period_id', $periodId)->first()->lgu_id;
        $assessment = AssessmentQuestionnaire::where('period_id', $periodId)
            ->where('lgu_id', $lguId)
            ->where('questionnaire_id', $questionnaireId)
            ->first();

        $selectedLevelId = $assessment->questionnaire_level_id ?? 0;
        $references = $periodHelper->getNavigation($questionnaireTreeId, $questionnaireId, $lguId, $periodId);

        $means = MeansOfVerification::where('questionnaire_id', $questionnaireId)->get();
        $levels = QuestionnaireLevel::where('questionnaire_id', $questionnaireId)->get();

        // Assemble: set session for all variables
        session([
            'rootId' => $currentRoot->id,
            'questionnaireTreeId' => $questionnaireTreeId,
            'lguId' => $lguId,
            'parentId' => $parent->id,
            'childId' => $child->id,
        ]);

        return redirect(route('assessment-management'));

        // return view('questionnaires.view', compact('questionnaire', 
        //     'currentRoot', 'roots',
        //     'questionnaireId', 'periodId', 'lguId', 'selectedLevelId',
        //     'child', 'parent', 'references', 'means', 'levels'));
    }

    public function getReference($questionnaireTreeId, $referenceId)
    {
        $rootId = $lguId = $periodId = 0;
        $periodHelper = new PeriodHelper();
        $questionnaire = QuestionnaireTree::find($questionnaireTreeId);
        $references = $periodHelper->getNavigation($questionnaireTreeId, $rootId, $lguId, $periodId);
        $child = $this->getSingleQuestionnaire($referenceId);
        $parent = $child->parent;
        $root = $this->getRootQuestionnaire($child);

        $means = MeansOfVerification::where('questionnaire_id', $child->id)->get();
        $levels = QuestionnaireLevel::where('questionnaire_id', $child->id)->get();

        return view('questionnaires.view', compact('root', 'questionnaire', 'child', 'parent', 'references', 'means', 'levels'));
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

    private function getRootQuestionnaire($child)
    {
        if ($child->parent_id == 0) {
            return $child;
        }

        $parent = $child->parent()->first();
        return $this->getRootQuestionnaire($parent);
    }

    private function buildTree($parent): array
    {
        $children = $parent->children()->get();
        $means = DB::table('questionnaire_means_of_verifications')
            ->leftJoin('means_of_verifications', 'means_of_verifications.id', '=', 'questionnaire_means_of_verifications.means_of_verification_id')
            ->where('questionnaire_means_of_verifications.questionnaire_id', $parent->id)
            ->get();

        return [
            'parent' => $parent->toArray(),
            'children' => $children->map(function ($item) {
                return $this->buildTree($item);
            })
        ];
    }
}
