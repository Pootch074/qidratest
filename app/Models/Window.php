<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Window extends Model
{
    protected $fillable = ['category_id', 'window_number'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($window) {
            // Prevent duplicate window 1 for the same step
            if ($window->window_number == 1) {
                $exists = Window::where('step_id', $window->step_id)
                    ->where('window_number', 1)
                    ->exists();

                if ($exists) {
                    throw new \Exception('Window number 1 already exists for this step.');
                }
            }
        });
    }

    public function step()
    {
        return $this->belongsTo(Category::class, 'category_id');
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
