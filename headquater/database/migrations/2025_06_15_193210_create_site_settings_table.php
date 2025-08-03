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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');

            // General Settings
            $table->integer('data_retention_days')->default(90);

            // Module Toggles
            $table->boolean('checkout_tracking_enabled')->default(true);
            $table->boolean('fraud_blocker_enabled')->default(true);
            $table->boolean('courier_service_enabled')->default(true);

            // Advanced
            $table->boolean('delete_on_uninstall')->default(false);
            $table->boolean('debug_mode')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
