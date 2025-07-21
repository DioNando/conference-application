<?php

namespace Database\Factories;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $username = strtolower($firstName . '.' . $lastName);

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => fake()->optional(0.8)->passthrough($username),
            'email' => fake()->unique()->safeEmail(),
            'avatar' => fake()->optional(0.7)->imageUrl(150, 150, 'people'),
            'phone' => fake()->optional(0.6)->phoneNumber(),
            'role' => fake()->randomElement([
                UserRole::USER->value,
                UserRole::USER->value,
                UserRole::USER->value,
                UserRole::MANAGER->value,
                UserRole::GUEST->value,
            ]), // Biased towards 'user'
            'email_verified_at' => fake()->optional(0.8)->dateTimeThisYear(),
            'last_login' => fake()->optional(0.7)->dateTimeThisMonth(),
            'is_active' => fake()->boolean(80), // 80% are active
            'password' => static::$password ??= Hash::make('password'),
            'preferences' => [
                'theme' => fake()->randomElement(['light', 'dark', 'system']),
                'language' => fake()->randomElement(['en', 'fr']),
                'notifications' => fake()->boolean(70),
                'emailNotifications' => fake()->boolean(50),
            ],
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
