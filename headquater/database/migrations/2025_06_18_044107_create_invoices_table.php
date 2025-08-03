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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('site_id')->constrained()->onDelete('cascade');
        $table->string('status')->default('due'); // e.g., due, paid, cancelled
        $table->decimal('total_amount', 10, 2);
        $table->date('due_date');
        $table->timestamp('paid_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
