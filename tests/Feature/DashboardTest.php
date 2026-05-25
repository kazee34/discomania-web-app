<?php

namespace Tests\Feature;

use App\Models\AdminModel;
use App\Models\UserModel as User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_visit_the_dashboard()
    {
        $user = User::factory()->create();
        AdminModel::create(['user_id' => $user->id, 'role' => 'admin', 'is_active' => true]);
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_authenticated_customer_cannot_visit_the_dashboard()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertForbidden();
    }
}
