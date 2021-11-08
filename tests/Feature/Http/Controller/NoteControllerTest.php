<?php

namespace Tests\Feature\Http\Controller;

use App\Models\Note;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoteControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test listing all of a user notes
     *
     * @return void
     */
    public function test_list_notes()
    {
        $user = User::factory()
        ->has(Note::factory()->count(3))
        ->create();

        Sanctum::actingAs($user);

        $this->getJson(route('notes.index'))
            ->assertExactJson($user->notes->toArray())
            ->assertJsonCount(3)
            ->assertSuccessful();
    }

    /**
     * Test listing notes without being loged in
     *
     * @return void
     */

    public function test_unauth_list_notes()
    {
        $this->getJson(route('notes.index'))
        ->assertUnauthorized();
    }


}
