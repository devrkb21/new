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
            // Drop the old generic column
            $table->dropColumn('usage_fraud_blocks');

            // Add the new specific columns
            $table->unsignedInteger('usage_fraud_ips')->default(0)->after('usage_checkouts');
            $table->unsignedInteger('usage_fraud_emails')->default(0)->after('usage_fraud_ips');
            $table->unsignedInteger('usage_fraud_phones')->default(0)->after('usage_fraud_emails');
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
