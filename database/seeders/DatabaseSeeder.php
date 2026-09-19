<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Catalog\Database\Seeders\CatalogDatabaseSeeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // 2. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'mobile_number' => '+8801700000000',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);

        // 3. Customer User
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'John Customer',
                'password' => Hash::make('password'),
                'mobile_number' => '+8801800000000',
                'email_verified_at' => now(),
            ]
        );
        $customer->assignRole($customerRole);

        // 4. Catalog & Inventory Seeder
        $this->call(CatalogDatabaseSeeder::class);
    }
}
