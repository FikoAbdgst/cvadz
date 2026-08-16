<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_login_page(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk Admin');
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_can_login_with_seeded_credentials(): void
    {
        $this->seed(AdminSeeder::class);

        $this->post(route('login.attempt'), [
            'email' => 'admin@cvadz.com',
            'password' => 'admin123',
        ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Dashboard');
    }

    public function test_admin_cannot_login_with_wrong_password(): void
    {
        $this->seed(AdminSeeder::class);

        $this->post(route('login.attempt'), [
            'email' => 'admin@cvadz.com',
            'password' => 'salah123',
        ])
            ->assertSessionHasErrors('email')
            ->assertRedirect();

        $this->assertGuest();
    }

    public function test_authenticated_admin_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
