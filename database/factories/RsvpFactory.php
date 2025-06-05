<?php

namespace Database\Factories;

use App\Models\Rsvp;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class RsvpFactory extends Factory
{
    protected $model = Rsvp::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->numerify('##########'),
            'event_id' => Event::factory(),
        ];
    }

    public function withItems(int $count = 2): static
    {
        return $this->hasItems($count);
    }
}