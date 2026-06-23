<?php

namespace App\Console\Commands;

use App\Http\Controllers\WorkBreakdownStructureController;
use Illuminate\Console\Command;

class DeleteOldWbs extends Command
{
    protected $signature = 'app:delete-old-wbs';
    protected $description = 'Delete WBS Level 3 records older than one month';

    public function handle(): int
    {
        (new WorkBreakdownStructureController())->deleteWbsLevel3MoreOneMonth();
        $this->info('Old WBS Level 3 records deleted.');
        return Command::SUCCESS;
    }
}
