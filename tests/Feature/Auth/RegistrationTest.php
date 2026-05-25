<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register()
    {
        $response = $this->post(route('register.store'), [
            'first_name'              => 'Test',
            'last_name'               => 'User',
            'email'                   => 'test@example.com',
            'password'                => 'Password1!',
            'password_confirmation'   => 'Password1!',
            'shipping_street'         => 'Calle Mayor',
            'shipping_street_number'  => '1',
            'shipping_city'           => 'Madrid',
            'shipping_postal_code'    => '28001',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/shop');
    }
}
