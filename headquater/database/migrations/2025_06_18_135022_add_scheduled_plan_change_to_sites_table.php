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
            // The plan that will become active in the future.
            $table->foreignId('next_plan_id')->nullable()->after('price_id')->constrained('plans')->onDelete('set null');

            // The specific price (e.g., yearly) of the next plan.
            $table->foreignId('next_price_id')->nullable()->after('next_plan_id')->constrained('prices')->onDelete('set null');

            // The date on which the new plan should become active.
            $table->timestamp('plan_change_at')->nullable()->after('plan_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropForeign(['next_plan_id']);
            $table->dropForeign(['next_price_id']);
            $table->dropColumn(['next_plan_id', 'next_price_id', 'plan_change_at']);
        });
    }
};
