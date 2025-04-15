<?php

namespace App\Http\Controllers;

use App\Models\MeansOfVerification;
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

    public function manageQuestionnaires($questionnaireId)
    {

        $questionnaire = QuestionnaireTree::find($questionnaireId);
        $references = $this->getNavigation($questionnaireId);
        $child = $this->getFirstQuestionnaire($questionnaireId);
        $parent = $child->parent;
        $root = $this->getRootQuestionnaire($child);

        $means = MeansOfVerification::where('questionnaire_id', $child->id)->get();
        $levels = QuestionnaireLevel::where('questionnaire_id', $child->id)->get();

        return view('questionnaires.view', compact('root', 'questionnaire', 'child', 'parent', 'references', 'means', 'levels'));
    }

    public function getReference($questionnaireId, $referenceId)
    {
        $questionnaire = QuestionnaireTree::find($questionnaireId);
        $references = $this->getNavigation($questionnaireId);
        $child = $this->getSingleQuestionnaire($referenceId);
        $parent = $child->parent;
        $root = $this->getRootQuestionnaire($child);

        $means = MeansOfVerification::where('questionnaire_id', $child->id)->get();
        $levels = QuestionnaireLevel::where('questionnaire_id', $child->id)->get();

        return view('questionnaires.view', compact('root', 'questionnaire', 'child', 'parent', 'references', 'means', 'levels'));
    }

    private function getNavigation($id)
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
