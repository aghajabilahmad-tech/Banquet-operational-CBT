<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
        return [
            'username'       => fake()->unique()->numerify('2122100###'),
            'password'       => static::$password ??= Hash::make('password123'),
            'full_name'      => fake()->name(),
            'role'           => fake()->randomElement(['guru', 'siswa']),
            'class_name'     => 'XII Perhotelan 1',
            'phone'          => fake()->phoneNumber(),
            'is_active'      => true,
            'remember_token' => Str::random(10),
        ];
    }
}
