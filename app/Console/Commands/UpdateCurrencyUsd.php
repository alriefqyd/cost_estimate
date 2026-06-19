<?php

namespace App\Console\Commands;

use App\Http\Controllers\SettingController;
use Illuminate\Console\Command;

class UpdateCurrencyUsd extends Command
{
    protected $signature = 'app:update-currency-usd';
    protected $description = 'Fetch and update the latest USD exchange rate from external source';

    public function handle(): int
    {
        (new SettingController())->updateCurrencyUsd();
        $this->info('USD currency rate updated.');
        return Command::SUCCESS;
    }
}
