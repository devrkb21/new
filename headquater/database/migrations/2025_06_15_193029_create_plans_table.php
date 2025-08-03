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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Free", "Pro", "Agency"
            $table->string('slug')->unique(); // e.g., "free", "pro", "agency"
            $table->unsignedInteger('limit_checkouts');
            $table->unsignedInteger('limit_fraud_ips');
            $table->unsignedInteger('limit_fraud_emails');
            $table->unsignedInteger('limit_fraud_phones');
            $table->unsignedInteger('limit_courier_checks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
