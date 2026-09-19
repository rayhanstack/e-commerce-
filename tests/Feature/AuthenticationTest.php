<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_record_login_history(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin, 'admin');

        $this->assertDatabaseHas('login_histories', [
            'email' => 'admin@example.com',
            'status' => 'success',
        ]);
    }

    public function test_customer_can_login_and_record_login_history(): void
    {
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'customer@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($customer, 'web');

        $this->assertDatabaseHas('login_histories', [
            'email' => 'customer@example.com',
            'status' => 'success',
        ]);
    }
}
