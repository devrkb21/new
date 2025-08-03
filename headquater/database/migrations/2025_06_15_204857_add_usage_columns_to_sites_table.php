<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->unsignedInteger('usage_checkouts')->default(0)->after('status');
            $table->unsignedInteger('usage_fraud_blocks')->default(0)->after('usage_checkouts');
            $table->unsignedInteger('usage_courier_checks')->default(0)->after('usage_fraud_blocks');
            $table->timestamp('usage_period_ends_at')->nullable()->after('plan_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            //
        });
    }
};
