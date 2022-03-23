<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Илья',
            'username' => 'ilia',
            'password' => bcrypt('kavkazdag'), // password
            'remember_token' => Str::random(10),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'role_id' => 1,
        ];
    }
}
