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

    public function post(Request $request)
    {
        try {
            $validate = $request->validate([
                'questionnaire_name'    => 'required|string',
                'effectivity_date'      => 'required|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $sourceTreeId = QuestionnaireTree::whereIn('id', function ($query) {
            $query->select('questionnaire_tree_id')
                ->from('questionnaires');
        })
        ->orderByDesc('id')
        ->value('id');

        $validate['created_at'] = $validate['updated_at'] = Carbon::now();
        $validate['status'] = 'published'; // Published, Ended
        $questionnaire = QuestionnaireTree::create($validate);
        $newTreeId = $questionnaire->id;

        // clone questionnaires for the new tree
        $sourceQuestionnaires = Questionnaire::where('questionnaire_tree_id', $sourceTreeId)->get();
        foreach ($sourceQuestionnaires as $questionnaire) {
            $newQuestionnaire = $questionnaire->replicate(); // clones attributes
            $newQuestionnaire->questionnaire_tree_id = $newTreeId; // set to new tree ID
            $newQuestionnaire->save();
        }

        // return new questionnaire
        return response()->json(['message' => 'Questionnaire added successfully!', 'questionnaire' => [
            'questionnaire_name' => $questionnaire['questionnaire_name'],
            'effectivity_date' => $questionnaire['effectivity_date'],
            'status' => strtolower($questionnaire['status'])
        ]], 201);
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
