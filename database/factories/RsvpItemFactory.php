<?php

namespace Database\Factories;

use App\Models\RsvpItem;
use App\Models\Rsvp;
use Illuminate\Database\Eloquent\Factories\Factory;

class RsvpItemFactory extends Factory
{
    protected $model = RsvpItem::class;

    public function definition(): array
    {
        return [
            'item' => $this->faker->words(2, true),
            'rsvp_id' => Rsvp::factory(),
        ];
    }
}