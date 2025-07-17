<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PeriodHelper;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\PeriodAssessment;
use App\Models\PeriodAssessor;
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
                'questionnaire_tree_id' => $p->questionnaire_tree_id,
                'name' => $p->name,
                'lgu_id' => $p->lgu_id,
                'rmt_id' => $p->rmt_id,
                'start_date' => $p->start_date,
                'end_date' => $p->end_date,
                'status' => $p->status
            ];
        }));
    }

    /**
     * Creates a new assessment period.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        // Get the latest published questionnaire
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

        // Add the questionnaire ID to the request data
        $validate['questionnaire_tree_id'] = $questionnaire->id;
        $validate['status'] = 'ongoing';
        $validate['user_id'] = auth()->user()->id ?? $userId;

        $period = Period::create($validate);

        return response()->json([
            'message' => 'Assessment period added successfully!',
            'period' => [
                'questionnaire_tree_id' => $questionnaire->id,
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
            'questionnaire_tree_id' => $period['questionnaire_tree_id'],
            'name' => $period['name'],
            'start_date' => $period['start_date'],
            'end_date' => $period['end_date'],
            'status' => $period['status']
        ]], 201);

    }

    public function assign(Request $request)
    {
        $data = $request->all();

        $periodId = PeriodHelper::currentPeriodId();
        $id = $data['id'];
        $teamLeader = $data['team_leader'];
        $rmts = $data['members']; // array of user IDs to assign

        $periodAssessment = PeriodAssessment::find($id);

        // Assign team leader only if different
        if ($periodAssessment->user_id !== $teamLeader) {
            $periodAssessment->user_id = $teamLeader;
            $periodAssessment->save();
        }

        // Get currently assigned RMT user IDs
        $currentRmts = PeriodAssessor::where('period_assessment_id', $id)
            ->pluck('user_id')
            ->toArray();

        // Users to remove: in DB but not in new list
        $toRemove = array_diff($currentRmts, $rmts);

        // Users to add: in new list but not in DB
        $toAdd = array_diff($rmts, $currentRmts);

        // Remove outdated users
        if (!empty($toRemove)) {
            PeriodAssessor::where('period_assessment_id', $id)
                ->whereIn('user_id', $toRemove)
                ->delete();
        }

        // Add new users
        foreach ($toAdd as $userId) {
            PeriodAssessor::create([
                'period_assessment_id' => $id,
                'user_id' => $userId,
            ]);
        }

        return response()->json(['message' => 'RMT Management is completed.'], 200);
    }

}
