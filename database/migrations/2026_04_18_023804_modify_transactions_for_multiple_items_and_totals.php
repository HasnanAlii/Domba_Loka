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
        // Add new columns first
        Schema::table('transactions', function (Blueprint $table) {
            $table->date('transaction_date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('other_fees', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0); // global discount
            $table->decimal('downpayment', 15, 2)->default(0);
            $table->decimal('sisa', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('warehouse')->nullable();
        });

        // Migrate existing data to transaction_details
        $transactions = DB::table('transactions')->get();
        foreach ($transactions as $t) {
            DB::table('transaction_details')->insert([
                'transaction_id' => $t->id,
                'sheep_id' => $t->sheep_id,
                'quantity' => $t->quantity,
                'price' => $t->total_price / max($t->quantity, 1),
                'total_price' => $t->total_price,
                'created_at' => $t->created_at,
                'updated_at' => $t->updated_at,
            ]);

            DB::table('transactions')->where('id', $t->id)->update([
                'transaction_date' => date('Y-m-d', strtotime($t->created_at)),
                'due_date' => date('Y-m-d', strtotime($t->created_at)),
                'subtotal' => $t->total_price,
                'sisa' => $t->total_price, // Assuming no downpayment recorded previously
            ]);
        }

        // Drop foreign key and columns from transactions
        Schema::table('transactions', function (Blueprint $table) {
            // Need to drop foreign key first
            $table->dropForeign(['sheep_id']);
            $table->dropColumn(['sheep_id', 'quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('sheep_id')->nullable()->constrained('sheep')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
        });

        // Copy back from first detail
        $details = DB::table('transaction_details')->groupBy('transaction_id')->orderBy('id', 'asc')->get();
        foreach ($details as $d) {
            DB::table('transactions')->where('id', $d->transaction_id)->update([
                'sheep_id' => $d->sheep_id,
                'quantity' => DB::table('transaction_details')->where('transaction_id', $d->transaction_id)->sum('quantity'),
            ]);
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_date', 'due_date', 'subtotal', 'tax', 'other_fees', 
                'discount', 'downpayment', 'sisa', 'notes', 'warehouse'
            ]);
        });
    }
};
