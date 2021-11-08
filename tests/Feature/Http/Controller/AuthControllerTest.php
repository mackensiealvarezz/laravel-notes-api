<?php

namespace Tests\Feature\Http\Controller;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Testing successful login
     *
     * @return void
     */
    public function test_successful_login()
    {

        //create user
        $user = User::factory()->create();

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'created_at',
                'updated_at'
            ],
            'token'
        ])
        ->assertSuccessful();
    }
}
