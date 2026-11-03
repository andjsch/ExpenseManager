<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Determine if this migration should run.
     */
    public function shouldRun(): bool
    {
        return \App\Models\Bill::count() === 0;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bills', function(Blueprint $table) {
            $table->unsignedBigInteger('ledger_id')->after('id');

            $table->foreign('ledger_id')->references('id')->on('ledgers')->cascadeOnDelete();
        });

        Schema::table('expense_types', function (Blueprint $table) {
            $table->unsignedBigInteger('ledger_id')->after('id');

            $table->foreign('ledger_id')->references('id')->on('ledgers')->cascadeOnDelete();

            $table->dropIndex('expense_types_name_unique');
            $table->unique(['name', 'ledger_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ignore
    }
};
