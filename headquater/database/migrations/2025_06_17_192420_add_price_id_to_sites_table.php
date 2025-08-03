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
    Schema::table('sites', function (Blueprint $table) {
        $table->foreignId('price_id')->nullable()->after('plan_id')->constrained('prices')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('sites', function (Blueprint $table) {
        $table->dropForeign(['price_id']);
        $table->dropColumn('price_id');
    });
}
};
