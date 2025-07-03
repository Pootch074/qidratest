<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\QuestionnaireTree;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuestionnairesController extends Controller
{
    //
    public function get()
    {
        return response()->json(QuestionnaireTree::all()->map(function ($q) {
            return [
                'id' => $q->id,
                'questionnaire_name' => $q->questionnaire_name,
                'effectivity_date' => $q->effectivity_date,
                'status' => $q->status
            ];
        }));
    }

    /**
     * Create a new questionnaire with the same structure as the latest published questionnaire
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(Request $request)
    {
        // Validate the request
        try {
            $validate = $request->validate([
                'questionnaire_name'    => 'required|string',
                'effectivity_date'      => 'required|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Get the latest published questionnaire
        $sourceTreeId = QuestionnaireTree::whereIn('id', function ($query) {
            $query->select('questionnaire_tree_id')->from('questionnaires');
        })
        ->orderByDesc('id')
        ->value('id');

        // Create a new tree with the same structure as the source tree
        $validate['created_at'] = $validate['updated_at'] = Carbon::now();
        $validate['status'] = 'published';
        $newTree = QuestionnaireTree::create($validate);
        $newTreeId = $newTree->id;

        // Clone related questionnaires
        $sourceQuestionnaires = Questionnaire::where('questionnaire_tree_id', $sourceTreeId)->get();
        $idMap = []; // [old_id => new_id]
        foreach ($sourceQuestionnaires as $questionnaire) {
            $newQuestionnaire = $questionnaire->replicate();
            $newQuestionnaire->questionnaire_tree_id = $newTreeId;
            $newQuestionnaire->parent_id = 0; // temporarily default to 0
            $newQuestionnaire->save();

            $idMap[$questionnaire->id] = $newQuestionnaire->id;

            // Clone related questionnaire_levels
            $levels = \App\Models\QuestionnaireLevel::where('questionnaire_id', $questionnaire->id)->get();
            foreach ($levels as $level) {
                $newLevel = $level->replicate();
                $newLevel->questionnaire_id = $newQuestionnaire->id;
                $newLevel->save();
            }

            // Clone related means_of_verification
            $verifications = \App\Models\MeansOfVerification::where('questionnaire_id', $questionnaire->id)->get();
            foreach ($verifications as $verification) {
                $newVerification = $verification->replicate();
                $newVerification->questionnaire_id = $newQuestionnaire->id;
                $newVerification->save();
            }
        }

        foreach ($sourceQuestionnaires as $questionnaire) {
            $oldId = $questionnaire->id;
            $newId = $idMap[$oldId];
            $oldParentId = $questionnaire->parent_id;

            if ($oldParentId && isset($idMap[$oldParentId])) {
                $newParentId = $idMap[$oldParentId];
                Questionnaire::where('id', $newId)->update(['parent_id' => $newParentId]);
            }
        }

        return response()->json([
            'message' => 'Questionnaire added successfully!',
            'questionnaire' => [
                'questionnaire_name' => $newTree['questionnaire_name'],
                'effectivity_date' => $newTree['effectivity_date'],
                'status' => strtolower($newTree['status'])
            ]
        ], 201);
    }

    public function put($id, Request $request)
    {
        $q = QuestionnaireTree::find($id);

        if (!$q) {
            return response()->json(['message' => 'Questionnaire not found'], 404);
        }

        try {
            $validate = $request->validate([
                'questionnaire_name'    => 'required|string',
                'effectivity_date'      => 'required|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validate['updated_at'] = Carbon::now();
        $q->update($validate);

        return response()->json(['message' => 'Questionnaire added successfully!', 'questionnaire' => [
            'questionnaire_name' => $q['questionnaire_name'],
            'effectivity_date' => $q['effectivity_date'],
            'status' => $q['status']
        ]], 201);

    }

    public function delete($id)
    {

        $q = QuestionnaireTree::find($id); // Find the user by ID

        if (!$q) {
            return response()->json(['message' => 'Questionnaire not found'], 404);
        }

        $q->delete(); // Delete the user

        return response()->json(['message' => 'Questionnaire deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $q = QuestionnaireTree::find($id); // Find the user by ID

        if (!$q) {
            return response()->json(['message' => 'Questionnaire not found'], 404);
        }

        $q->status = $q->status == 'published' ? 'unpublished' : 'published';
        $q->save();

        return response()->json([
            'status' => $q->status, // 'published' or 'unpublished'
        ]);
    }
}
