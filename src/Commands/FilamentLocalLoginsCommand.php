<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Commands;

use Illuminate\Console\Command;

class FilamentLocalLoginsCommand extends Command
{
    public $signature = 'filament-local-logins';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
