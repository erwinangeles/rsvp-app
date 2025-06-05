<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'description' => $this->faker->paragraph,
            'uuid' => Str::lower(Str::random(8)),
            'user_id' => User::factory()->state([
                'password' => bcrypt('password'),
            ]),
            'banner_image' => 'https://placehold.co/1200x400/png?text=' . $title,
            'meta_image' => 'https://placehold.co/600x315/png?text=' . $title,
        ];
    }

    public function withRsvps(int $count = 3): static
    {
        return $this->hasRsvps($count);
    }
}