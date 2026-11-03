<?php

namespace App\Console\Commands;

use App\Models\Ledger;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

#[Signature('migrate:db-to-v2')]
#[Description('This command migrates the database from V1 to V2.')]
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

        Schema::table('users', function (Blueprint $table) {
            $table->id()->change();
            $table->boolean('is_admin', false)->after('password');
        });

        User::first()->update(['is_admin' => true]);

        Artisan::call('migrate --force');

        $ledger = Ledger::create(['name' => 'Default', 'currency' => 'EUR', 'date_format' => 'Y-m-d']);
        User::all()->each(fn (User $u) => $u->ledgers()->sync([$ledger->id]));

        Schema::table('bills', function(Blueprint $table) use($ledger) {
            $table->unsignedBigInteger('ledger_id')->after('id')->default($ledger->id);

            $table->foreign('ledger_id')->references('id')->on('ledgers')->cascadeOnDelete();
        });

        Schema::table('expense_types', function (Blueprint $table) use($ledger) {
            $table->unsignedBigInteger('ledger_id')->after('id')->default($ledger->id);

            $table->foreign('ledger_id')->references('id')->on('ledgers')->cascadeOnDelete();

            $table->dropIndex('expense_types_name_unique');
            $table->unique(['name', 'ledger_id']);
        });

        // remove the default value after creating the columns
        Schema::table('bills', function (Blueprint $table) {
            $table->unsignedBigInteger('ledger_id')->after('id')->change();
        });

        Schema::table('expense_types', function (Blueprint $table) {
            $table->unsignedBigInteger('ledger_id')->after('id')->change();
        });
    }
}
