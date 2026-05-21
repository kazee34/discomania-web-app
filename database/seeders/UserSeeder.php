<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cliente de prueba
        $customer = UserModel::create([
            'name'               => 'Cliente Prueba',
            'email'              => 'cliente@discomania.test',
            'password'           => Hash::make('password'),
            'email_verified_at'  => now(),
        ]);

        CustomerModel::create([
            'user_id'    => $customer->id,
            'first_name' => 'Cliente',
            'last_name'  => 'Prueba',
            'phone'      => '600000001',
            'shipping_street'        => 'Calle Mayor',
            'shipping_street_number' => '1',
            'shipping_city'          => 'Madrid',
            'shipping_postal_code'   => '28001',
            'shipping_state_province'=> 'Madrid',
            'shipping_country'       => 'España',
        ]);

        // Admin de prueba
        $admin = UserModel::create([
            'name'               => 'Admin Prueba',
            'email'              => 'admin@discomania.test',
            'password'           => Hash::make('password'),
            'email_verified_at'  => now(),
        ]);

        AdminModel::create([
            'user_id'   => $admin->id,
            'role'      => 'admin',
            'is_active' => true,
        ]);
    }
}
