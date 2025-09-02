<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Window extends Model
{
    protected $fillable = ['step_id', 'section_id', 'window_number'];

    public function step()
    {
        return $this->belongsTo(Step::class, 'step_id');
    }

    /**
     * Check if a window already exists for a given step & section
     */
    public static function existsForStepAndSection($stepId, $sectionId, $windowNumber)
    {
        return self::where('step_id', $stepId)
            ->where('section_id', $sectionId)
            ->where('window_number', $windowNumber)
            ->exists();
    }
}
