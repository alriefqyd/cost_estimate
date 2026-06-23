<?php

namespace App\Console\Commands;

use App\Http\Controllers\EstimateAllDisciplineController;
use Illuminate\Console\Command;

class DeleteOldEstimates extends Command
{
    protected $signature = 'app:delete-old-estimates';
    protected $description = 'Delete estimate discipline records older than one month';

    public function handle(): int
    {
        (new EstimateAllDisciplineController())->deleteEstimateDisciplineMoreOneMonth();
        $this->info('Old estimate discipline records deleted.');
        return Command::SUCCESS;
    }
}
