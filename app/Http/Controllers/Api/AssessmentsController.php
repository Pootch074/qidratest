<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssessmentMean;
use App\Models\AssessmentQuestionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentsController extends Controller
{
    //
    public function mov(Request $request)
    {
        $createdId = null;
        $removedId = null;

        DB::transaction(function () use ($request, &$createdId, &$removedId) {
            // Define the common query
            $query = AssessmentMean::where('period_id', $request->period_id)
                ->where('lgu_id', $request->lgu_id)
                ->where('questionnaire_id', $request->questionnaire_id)
                ->where('means_id', $request->mov_id);

            if ($request->is_checked) {
                // Only add if it does not already exist
                if (!$query->exists()) {
                    $mean = new AssessmentMean();
                    $mean->period_id = $request->period_id;
                    $mean->lgu_id = $request->lgu_id;
                    $mean->questionnaire_id = $request->questionnaire_id;
                    $mean->means_id = $request->mov_id;
                    $mean->user_id = $request->user_id;
                    $mean->save();

                    $createdId = $mean->id;
                }
            } else {
                // Remove if it exists
                $existing = $query->first();
                if ($existing) {
                    $removedId = $existing->id;
                    $existing->delete();
                }
            }
        });

        return response()->json([
            'success' => true,
            'created_id' => $createdId,
            'removed_id' => $removedId,
        ]);
    }

    public function level(Request $request)
    {
        $level = AssessmentQuestionnaire::updateOrCreate(
            [
                'period_id' => $request->period_id,
                'lgu_id' => $request->lgu_id,
                'questionnaire_id' => $request->questionnaire_id,
            ],
            [
                'questionnaire_level_id' => $request->level_id,
                'user_id' => $request->user_id,
            ]
        );

        return response()->json([
            'success' => true,
            'created_id' => $level->id,
            'wasRecentlyCreated' => $level->wasRecentlyCreated,
        ]);
    }

    public function remarks(Request $request)
    {
        $record = AssessmentQuestionnaire::updateOrCreate(
            [
                'period_id' => $request->period_id,
                'lgu_id' => $request->lgu_id,
                'questionnaire_id' => $request->questionnaire_id,
            ],
            [
                'user_id' => $request->user_id,
                'remarks' => $request->remarks,
            ]
        );

        return response()->json(['success' => true, 'id' => $record->id]);
    }

    public function recommendation(Request $request)
    {
        $record = AssessmentQuestionnaire::updateOrCreate(
            [
                'period_id' => $request->period_id,
                'lgu_id' => $request->lgu_id,
                'questionnaire_id' => $request->questionnaire_id,
            ],
            [
                'user_id' => $request->user_id,
                'recommendations' => $request->recommendations,
            ]
        );

        return response()->json(['success' => true, 'id' => $record->id]);
    }

}
