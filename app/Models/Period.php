<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Period extends Model
{

    protected $table = 'periods';
    protected $primaryKey = 'id';
    protected $fillable = ['questionnaire_id', 'name', 'start_date', 'end_date', 'status', 'user_id'];
    protected $dates = ['start_date', 'end_date'];

    public function assessments(): HasMany
    {
        return $this->hasMany(PeriodAssessment::class);
    }

    protected static function booted(): void
    {
        static::created(function (Period $period) {

            // Wrap everything in a DB transaction for safety
            DB::transaction(function () use ($period) {

                // Grab all LGU IDs (chunk if you have thousands)
                $lguIds = Lgu::pluck('id');

                // Build one big insert payload (faster than looping saves)
                $rows = $lguIds->map(fn ($id) => [
                    'period_id'  => $period->id,
                    'lgu_id'     => $id,
                    'user_id'     => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ])->all();

                PeriodAssessment::insert($rows);

                // mark other period statuses to completed
                Period::where('id', '!=', $period->id)->update(['status' => 'completed']);
            });

        });
    }
}
