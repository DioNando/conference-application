<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'last_login' => now(),
            'preferences' => [
                'theme' => 'dark',
                'language' => 'en',
                'notifications' => true,
                'emailNotifications' => true,
            ],
        ]);
        $admin->assignRole(UserRole::ADMIN->value);

        // Create manager user
        $manager = User::create([
            'first_name' => 'Manager',
            'last_name' => 'User',
            'email' => 'manager@example.com',
            'username' => 'manager',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'preferences' => [
                'theme' => 'light',
                'language' => 'fr',
                'notifications' => true,
                'emailNotifications' => false,
            ],
        ]);
        $manager->assignRole(UserRole::MANAGER->value);

        // Create regular user
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'phone' => '+33612345678',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'preferences' => [
                'theme' => 'system',
                'language' => 'en',
                'notifications' => false,
                'emailNotifications' => false,
            ],
        ]);
        $user->assignRole(UserRole::USER->value);
    }
}
