<?php

namespace Database\Seeders;

use App\Models\Sheep;
use App\Models\SheepType;
use Illuminate\Database\Seeder;

class SheepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have some sheep types first
        $types = [
            ['name' => 'Garut', 'description' => 'Domba asli Jawa Barat yang terkenal tangguh.'],
            ['name' => 'Texel', 'description' => 'Domba penghasil daging yang sangat baik.'],
            ['name' => 'Merino', 'description' => 'Domba penghasil wol berkualitas tinggi.'],
            ['name' => 'Suffolk', 'description' => 'Domba dengan ciri khas kepala dan kaki hitam.'],
        ];

        foreach ($types as $type) {
            SheepType::firstOrCreate(['name' => $type['name']], $type);
        }

        // Create 20 sheep
        Sheep::factory()->count(20)->create([
            'type_id' => function () {
                return SheepType::inRandomOrder()->first()->id;
            }
        ]);
    }
}
