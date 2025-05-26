<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\QuestionnaireTree;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PeriodsController extends Controller
{
    //
    public function get()
    {
        return response()->json(Period::all()->map(function ($p) {
            return [
                'id' => $p->id,
                'questionnaire_id' => $p->questionnaire_id,
                'name' => $p->name,
                'lgu_id' => $p->lgu_id,
                'rmt_id' => $p->rmt_id,
                'start_date' => $p->start_date,
                'end_date' => $p->end_date,
                'status' => $p->status
            ];
        }));
    }

    public function post(Request $request)
    {
        if (!auth()->check()) {
            $userId = 1;
            // return redirect()->route('login'); // assumes your login route is named 'login'
        }

        try {
            $validate = $request->validate([
                'name'       => 'required|string',
                'start_date' => 'required|date',
                'end_date'   => 'required|date'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $questionnaire = QuestionnaireTree::orderByRaw("
            CASE
                WHEN status = 'published' THEN 0
                WHEN status = 'unpublished' THEN 1
                ELSE 2
            END
        ")->orderBy('id', 'desc')->first();

        if (!$questionnaire) {
            return response()->json(['error' => 'No questionnaire found.'], 404);
        }

        $validate['questionnaire_id'] = $questionnaire->id;
        $validate['status'] = 'ongoing';
        $validate['user_id'] = auth()->user()->id ?? $userId;

        $period = Period::create($validate);

        return response()->json([
            'message' => 'Assessment period added successfully!',
            'period' => [
                'questionnaire_id' => $questionnaire->id,
                'name'             => $period->name,
                'start_date'       => $period->start_date,
                'end_date'         => $period->end_date,
                'status'           => $period->status
            ]
        ], 201);
    }

    public function put($id, Request $request)
    {
        $period = Period::find($id);

        if (!$period) {
            return response()->json(['message' => 'Assessment period not found'], 404);
        }

        try {
            $validate = $request->validate([
                'name'              => 'required|string',
                'start_date'        => 'required|date',
                'end_date'          => 'required|date'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $validate['updated_at'] = Carbon::now();
        $period->update($validate);

        return response()->json(['message' => 'Assessment period added successfully!', 'period' => [
            'questionnaire_id' => $period['questionnaire_id'],
            'name' => $period['name'],
            'start_date' => $period['start_date'],
            'end_date' => $period['end_date'],
            'status' => $period['status']
        ]], 201);

    }

    public function delete($id)
    {

        $p = Period::find($id); // Find the user by ID

        if (!$p) {
            return response()->json(['message' => 'Assessment period not found'], 404);
        }

        $p->delete(); // Delete the user

        return response()->json(['message' => 'Assessment period deleted successfully']);
    }
}
