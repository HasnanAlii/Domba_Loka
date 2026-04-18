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
    Schema::create('growths', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sheep_id')->constrained('sheep')->cascadeOnDelete();
        $table->decimal('weight', 8, 2);
        $table->decimal('target', 8, 2);
        $table->date('date');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growths');
    }
};
