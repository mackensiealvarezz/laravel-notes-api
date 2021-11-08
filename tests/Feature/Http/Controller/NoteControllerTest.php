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


    /**
     * Test creating a note
     *
     * @return void
     */
    public function test_creating_note()
    {
        $user = User::factory()
        ->create();

        Sanctum::actingAs($user);

        $note = [
            'title' => 'new title',
            'note' => 'this is brand new'
        ];

        $this->postJson(route('notes.store'), $note)
        ->assertSuccessful();

        $this->assertDatabaseHas('notes',$note);
    }

    /**
     * Test creating a note without being loged in
     *
     * @return void
     */

    public function test_unauth_creating_note()
    {
        $note = [
            'title' => 'new title',
            'note' => 'this is brand new'
        ];

          $this->postJson(route('notes.store'), $note)
        ->assertUnauthorized();

        $this->assertDatabaseMissing('notes', $note);
    }

    /**
     * Test vlidation for creating a note
     *
     * @return void
     */

    public function test_title_validation_create_note()
    {

        $user = User::factory()
        ->create();

        Sanctum::actingAs($user);

        $note = [
            'note' => 'this is brand new'
        ];

        $this->postJson(route('notes.store'), $note)
        ->assertJsonValidationErrors('title');

        $this->assertDatabaseMissing('notes', $note);
    }

    public function test_note_validation_create_note()
    {

        $user = User::factory()
        ->create();

        Sanctum::actingAs($user);

        $note = [
            'title' => 'this is brand new'
        ];

        $this->postJson(route('notes.store'), $note)
        ->assertJsonValidationErrors('note');

        $this->assertDatabaseMissing('notes', $note);
    }








}
