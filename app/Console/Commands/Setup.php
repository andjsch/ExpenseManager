<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('setup')]
#[Description('Sets up the app for first use.')]
class Setup extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('key:generate');
        $this->call('migrate', ['--force' => true]);
    }
}
