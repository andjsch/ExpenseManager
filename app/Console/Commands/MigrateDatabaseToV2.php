<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Mailer\Header\MetadataHeader;

#[Signature('migrate:db-to-v2')]
#[Description('This command migrates the database from V1 to be compatible to V2.')]
class MigrateDatabaseToV2 extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        Schema::table('expense_types', function (Blueprint $table) {
            $table->dropForeign('expense_types_parent_foreign');
            $table->dropColumn('parent');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->renameColumn('type', 'expense_type_id');
        });
    }
}
