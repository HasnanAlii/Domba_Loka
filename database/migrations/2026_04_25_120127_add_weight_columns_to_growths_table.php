<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('growths', function (Blueprint $table) {
            // Berat domba sebelum dicatat (diambil dari sheep.weight saat store)
            $table->decimal('previous_weight', 8, 2)->default(0)->after('weight');
            // Berat aktual yang diinput user
            $table->decimal('actual_weight', 8, 2)->default(0)->after('previous_weight');
        });
    }

    public function down(): void
    {
        Schema::table('growths', function (Blueprint $table) {
            $table->dropColumn(['previous_weight', 'actual_weight']);
        });
    }
};
