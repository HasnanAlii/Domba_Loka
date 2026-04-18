<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('type')->default('penjualan')->after('supplier_id');
        });

        DB::table('transactions')
            ->whereNotNull('supplier_id')
            ->update(['type' => 'pembelian']);

        DB::table('transactions')
            ->whereNotNull('customer_id')
            ->update(['type' => 'penjualan']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
