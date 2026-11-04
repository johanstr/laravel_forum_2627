<?php

namespace Database\Seeders;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Altijd eerst de administrator aanmaken
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // We maken nog een aantal extra gewone users aan
        User::factory(9)->create();

        // We willen ook nog een aantal threads, topics en replies aanmaken
        // om onze app te kunnen testen
        Thread::factory(20)->create();
        Topic::factory(50)->create();
        Reply::factory(200)->create();
    }
}
