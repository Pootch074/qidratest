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

        $validate['created_at'] = $validate['updated_at'] = Carbon::now();
        $validate['status'] = 'Unpublished'; // Published, Ended
        $questionnaire = QuestionnaireTree::create($validate);

        return response()->json(['message' => 'Questionnaire added successfully!', 'questionnaire' => [
            'questionnaire_name' => $questionnaire['questionnaire_name'],
            'effectivity_date' => $questionnaire['effectivity_date'],
            'status' => $questionnaire['status']
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

        return response()->json(['message' => 'User added successfully!', 'questionnaire' => [
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
}
