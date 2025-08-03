<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssessmentMean;
use App\Models\AssessmentQuestionnaire;
use App\Models\AssessmentRecommendation;
use App\Models\AssessmentRemark;
use App\Models\PeriodAssessment;
use Carbon\Carbon;
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

        $this->checkFirstAssessment($request->period_id, $request->lgu_id);
        $this->checkLastAssessment($request->period_id, $request->lgu_id);

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

        $this->checkFirstAssessment($request->period_id, $request->lgu_id);
        $this->checkLastAssessment($request->period_id, $request->lgu_id);

        return response()->json([
            'success' => true,
            'created_id' => $level->id,
            'wasRecentlyCreated' => $level->wasRecentlyCreated,
        ]);
    }

    public function remarks(Request $request)
    {
        $data = $request->validate([
            'period_id'        => ['required', 'integer'],
            'lgu_id'           => ['required', 'integer'],
            'questionnaire_id' => ['required', 'integer'],
            'user_id'          => ['required', 'integer'],
            'remarks'          => ['nullable', 'string'],
        ]);

        $keys = [
            'period_id'        => $data['period_id'],
            'lgu_id'           => $data['lgu_id'],
            'questionnaire_id' => $data['questionnaire_id'],
        ];

        $normalized = $this->normalizeQuillHtml($data['remarks'] ?? null);

        if ($normalized === null) {
            // Content empty → delete existing entry if present
            $existing = AssessmentRemark::where($keys)->first();

            if ($existing) {
                $existing->delete();
            }

            // Run your other checks (if they rely on counts, this will reflect deletion)
            $this->checkFirstAssessment($data['period_id'], $data['lgu_id']);
            $this->checkLastAssessment($data['period_id'], $data['lgu_id']);

            return response()->json([
                'success' => true,
                'deleted' => (bool) $existing,
                'id'      => null,
            ]);
        }

        // Non-empty → upsert
        $record = AssessmentRemark::updateOrCreate(
            $keys,
            [
                'user_id' => $data['user_id'],
                'remarks' => $normalized, // not null here
            ]
        );

        $this->checkFirstAssessment($data['period_id'], $data['lgu_id']);
        $this->checkLastAssessment($data['period_id'], $data['lgu_id']);

        return response()->json(['success' => true, 'id' => $record->id]);
    }

    public function recommendation(Request $request)
    {
        $data = $request->validate([
            'period_id'        => ['required', 'integer'],
            'lgu_id'           => ['required', 'integer'],
            'questionnaire_id' => ['required', 'integer'],
            'user_id'          => ['required', 'integer'],
            'recommendations'  => ['nullable', 'string'],
        ]);

        $keys = [
            'period_id'        => $data['period_id'],
            'lgu_id'           => $data['lgu_id'],
            'questionnaire_id' => $data['questionnaire_id'],
        ];

        $normalized = $this->normalizeQuillHtml($data['recommendations'] ?? null);

        if ($normalized === null) {
            // Content empty → delete existing entry if present
            $existing = AssessmentRecommendation::where($keys)->first();

            if ($existing) {
                $existing->delete();
            }

            $this->checkFirstAssessment($data['period_id'], $data['lgu_id']);
            $this->checkLastAssessment($data['period_id'], $data['lgu_id']);

            return response()->json([
                'success' => true,
                'deleted' => (bool) $existing,
                'id'      => null,
            ]);
        }

        // Non-empty → upsert
        $record = AssessmentRecommendation::updateOrCreate(
            $keys,
            [
                'user_id' => $data['user_id'],
                'recommendations' => $normalized,
            ]
        );

        $this->checkFirstAssessment($data['period_id'], $data['lgu_id']);
        $this->checkLastAssessment($data['period_id'], $data['lgu_id']);

        return response()->json(['success' => true, 'id' => $record->id]);
    }

    /**
     * If this is the first assessment, set the start date to now.
     *
     * @param int $periodId
     * @param int $lguId
     * @return void
     */
    private function checkFirstAssessment($periodId, $lguId): void
    {
        $assessment = PeriodAssessment::where('period_id', $periodId)
            ->where('lgu_id', $lguId)
            ->first();

        if ($assessment && is_null($assessment->assessment_start_date)) {
            // Update the date to now
            $assessment->assessment_start_date = Carbon::now();
            $assessment->status = PeriodAssessment::STATUS_ONGOING;
            $assessment->save();
        }
    }

    private function checkLastAssessment($periodId, $lguId)
    {

    }

    /**
     * Normalize Quill/Tiptap "empty" HTML to null.
     * Handles <p><br></p>, <div><br></div>, <p></p>, only <br>, whitespace, &nbsp;, zero-width chars.
     */
    protected function normalizeQuillHtml(?string $html): ?string
    {
        if ($html === null) return null;

        $s = trim($html);

        // Normalize non-breaking spaces and remove zero-width chars
        $s = str_replace(["&nbsp;", "\u{00A0}", "\xC2\xA0"], ' ', $s);
        $s = preg_replace("/\x{200B}|\x{200C}|\x{200D}|\x{FEFF}/u", '', $s);

        // Remove empty wrapper blocks like <p><br></p>, <p></p>, <div><br></div>
        $s = preg_replace('#(?i)<(p|div)>\s*(<br\s*/?>\s*)*\s*</\1>#', '', $s);

        // If it’s only <br> tags, drop them
        $s = preg_replace('#(?i)^(<br\s*/?>\s*)+$#', '', $s);

        // If no text remains after stripping tags, treat as empty
        $text = trim(strip_tags($s));
        if ($text === '') {
            return null;
        }

        // Optionally return cleaned HTML
        return trim($s);
    }

}
