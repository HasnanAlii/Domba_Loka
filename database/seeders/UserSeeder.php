<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ======================
        // PERMISSIONS
        // ======================
        $permissions = [
            'view sheep',
            'create sheep',
            'edit sheep',
            'delete sheep',

            'view transaction',
            'create transaction',
            'edit transaction',
            'delete transaction',

            'view finance',
            'manage finance',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ======================
        // ROLES
        // ======================
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $cashierRole = Role::firstOrCreate(['name' => 'cashier']);

        // assign permission ke role
        $adminRole->givePermissionTo(Permission::all());

        $cashierRole->givePermissionTo([
            'view sheep',
            'view transaction',
            'create transaction',
        ]);

        // ======================
        // USERS
        // ======================
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        $cashier = User::firstOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Kasir',
                'password' => Hash::make('password'),
            ]
        );
        $cashier->assignRole($cashierRole);
    }
}
