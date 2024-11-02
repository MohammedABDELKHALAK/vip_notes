<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $users = User::all();
        return [
            // 'user_id' => $users->random()->id,
            'title' => $this->faker->words(3, true), // 3-word title
            'content' => $this->faker->realText(500),
            'access_token' => Str::random(32)
        ];
    }
}
