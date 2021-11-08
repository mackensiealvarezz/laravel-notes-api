<?php

namespace Database\Seeders;

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
        ->create([
            'email' => 'user1@test.com'
        ]);

        User::factory()
        ->create([
            'email' => 'user2@test.com'
        ]);

        User::factory()
        ->create([
            'email' => 'user3@test.com'
        ]);
    }
}
