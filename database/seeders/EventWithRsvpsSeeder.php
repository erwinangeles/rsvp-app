<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Rsvp;

class EventWithRsvpsSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(5)
            ->state([
                'password' => bcrypt('password'),
            ])
            ->has(
                Event::factory()
                    ->count(rand(1,5))
                    ->has(
                        Rsvp::factory()
                            ->count(3)
                            ->hasItems(2)
                    )
            )
            ->create();
    }
}