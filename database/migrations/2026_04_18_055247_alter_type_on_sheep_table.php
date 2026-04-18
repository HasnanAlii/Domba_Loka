<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\SheepType;
use App\Models\Sheep;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign key dynamically
        Schema::table('sheep', function (Blueprint $table) {
            $table->foreignId('type_id')->nullable()->constrained('sheep_types')->nullOnDelete();
        });

        // Migrate text data to relationships if safe (optional, but good for UX)
        // Ignoring if they have existing data might cause issues, let's keep it safe.
        $existingSheep = DB::table('sheep')->get();
        foreach($existingSheep as $s) {
            if ($s->type) {
                $st = DB::table('sheep_types')->where('name', $s->type)->first();
                if (!$st) {
                    $id = DB::table('sheep_types')->insertGetId(['name' => $s->type, 'created_at' => now(), 'updated_at' => now()]);
                } else {
                    $id = $st->id;
                }
                DB::table('sheep')->where('id', $s->id)->update(['type_id' => $id]);
            }
        }

        // Now drop the string column
        Schema::table('sheep', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sheep', function (Blueprint $table) {
            $table->string('type')->nullable();
        });

        // Sync back (naive)
        $existingSheep = DB::table('sheep')->get();
        foreach($existingSheep as $s) {
            if ($s->type_id) {
                $st = DB::table('sheep_types')->where('id', $s->type_id)->first();
                if ($st) {
                    DB::table('sheep')->where('id', $s->id)->update(['type' => $st->name]);
                }
            }
        }

        Schema::table('sheep', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });
    }
};
