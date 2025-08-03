<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('prices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('plan_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 8, 2);
        $table->string('period')->comment('e.g., monthly, yearly');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
