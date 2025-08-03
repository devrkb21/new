<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In the new ...create_billing_periods_table.php migration file
public function up(): void
{
    Schema::create('billing_periods', function (Blueprint $table) {
        $table->id();
        $table->string('name')->comment('e.g., Monthly, Yearly');
        $table->string('slug')->unique()->comment('e.g., monthly, yearly');
        $table->integer('duration_in_days');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_periods');
    }
};
