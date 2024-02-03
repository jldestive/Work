<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user =  User::factory()->create();
        $statudes = ['Open', 'Closed'];

        return [
            'description' => fake()->realText(),
            'status' => $statudes[array_rand($statudes)],
            'user_id' => $user->id
        ];
    }
}
