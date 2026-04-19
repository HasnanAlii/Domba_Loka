<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing NULL values to 0 first
        DB::table('sheep')->whereNull('age')->update(['age' => 0]);

        Schema::table('sheep', function (Blueprint $table) {
            $table->integer('age')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sheep', function (Blueprint $table) {
            $table->integer('age')->nullable()->default(null)->change();
        });
    }
};
