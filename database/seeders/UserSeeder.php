<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
        ->has(Note::factory()->count(10))
        ->create([
            'email' => 'user1@test.com'
        ]);

        User::factory()
        ->has(Note::factory()->count(3))
        ->create([
            'email' => 'user2@test.com'
        ]);

        User::factory()
        ->has(Note::factory()->count(5))
        ->create([
            'email' => 'user3@test.com'
        ]);
    }
}
