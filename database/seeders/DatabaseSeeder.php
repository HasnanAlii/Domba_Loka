<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // reset cache permission (WAJIB untuk spatie)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call([
            UserSeeder::class,
            // SheepSeeder::class,
        ]);
    }
}