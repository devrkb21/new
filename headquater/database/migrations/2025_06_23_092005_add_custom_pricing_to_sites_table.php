<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            // Add columns for custom pricing, place them after custom_limits
            $table->decimal('custom_price_amount', 8, 2)->nullable()->after('custom_limits');
            $table->foreignId('custom_billing_period_id')->nullable()->after('custom_price_amount')->constrained('billing_periods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropForeign(['custom_billing_period_id']);
            $table->dropColumn(['custom_price_amount', 'custom_billing_period_id']);
        });
    }
};