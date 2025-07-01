<?php

namespace App\Console\Commands;

use App\Helpers\PeriodHelper;
use Illuminate\Console\Command;

class GenerateAssessment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assessment:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate assessments for all LGUs.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // get active period
        $period = PeriodHelper::currentPeriod();
        $periodId = $period->id;
        $questionnaireId = $period->questionnaire_id;

        // generate assessment
    }
}
