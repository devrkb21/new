<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In the new ...alter_prices_table_for_billing_periods.php migration file
public function up(): void
{
    Schema::table('prices', function (Blueprint $table) {
        $table->dropColumn('period');
        $table->foreignId('billing_period_id')->nullable()->after('plan_id')->constrained()->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('prices', function (Blueprint $table) {
        $table->string('period')->after('plan_id');
        $table->dropForeign(['billing_period_id']);
        $table->dropColumn('billing_period_id');
    });
}
};
