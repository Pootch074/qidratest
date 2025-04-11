<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
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

    public function manageQuestionnaires($id, Request $request)
    {
        $data = [];
        $questionnaireTree = QuestionnaireTree::find($id);
        $parents = Questionnaire::where('questionnaire_tree_id', $questionnaireTree->id)
            ->where('parent_id', 0)->get();

        foreach ($parents as $parent) {
            $data[] = $this->buildTree($parent);
        }

        return view('questionnaires.view', compact('data'));
    }

    private function buildTree($parent): array
    {
        $children = $parent->children()->get();
        $means = DB::table('questionnaire_means_of_verifications')
            ->leftJoin('means_of_verifications', 'means_of_verifications.id', '=', 'questionnaire_means_of_verifications.means_of_verification_id')
            ->where('questionnaire_means_of_verifications.questionnaire_id', $parent->id)
            ->get();

        return [
            'parent' => $parent,
            'means' => $means,
            'children' => $children->map(function ($item) {
                return $this->buildTree($item);
            })
        ];
    }
}
