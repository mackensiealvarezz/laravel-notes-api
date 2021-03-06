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

        $this->assertDatabaseHas('notes', $note);
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


    /**
     * Test listing all of a user notes
     *
     * @return void
     */
    public function test_show_note()
    {

        $user = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        $note = $user->notes()->first();

        Sanctum::actingAs($user);

        $this->getJson(route('notes.show', ['note' => $note->id]))
            ->assertExactJson($note->toArray())
            ->assertSuccessful();
    }

    /**
     * Test listing notes without being loged in
     *
     * @return void
     */

    public function test_unauth_show_note()
    {
        $this->getJson(route('notes.show', 1))
            ->assertUnauthorized();
    }

    /**
     * Test can't acccess another user note
     *
     * @return void
     */

    public function test_unauth_show_someone_else_note()
    {

        $user = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        $user2 = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        $note = $user->notes()->first();

        Sanctum::actingAs($user2);

        $this->getJson(route('notes.show', ['note' => $note->id]))
            ->assertStatus(403);
    }

    /**
     * Test updating a note
     *
     * @return void
     */
    public function test_update_note()
    {

        $user = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        $note = $user->notes()->first();

        $newChanges = [
            'title' => 'new title',
            'note'  => 'testing',
        ];

        Sanctum::actingAs($user);

        //check that the record is there
        $this->assertDatabaseHas('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);

        $this->putJson(route('notes.update', ['note' => $note->id]), $newChanges)
            ->assertSuccessful();

        //validate changes
        $this->assertDatabaseMissing('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);
        $this->assertDatabaseHas('notes', $newChanges);
    }

    /**
     * Test updating a note without loged in
     * @return void
     */

    public function test_unauth_update_note()
    {
        $this->getJson(route('notes.show', 1))
            ->assertUnauthorized();
    }

    /**
     * Test can't acccess another user note
     *
     * @return void
     */

    public function test_unauth_update_someone_else_note()
    {

        $user = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        $user2 = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        $note = $user->notes()->first();

        $newChanges = [
            'title' => 'new title',
            'note'  => 'testing',
        ];

        //sign in as user 2
        Sanctum::actingAs($user2);

        //check that the record is there
        $this->assertDatabaseHas('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);

        $this->putJson(route('notes.update', ['note' => $note->id]), $newChanges)
            ->assertStatus(403);

        //check record hasn't changed
        $this->assertDatabaseHas('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);
    }

    /**
     * Test validating for update a note
     *
     * @return void
     */

    public function test_title_validation_update_note()
    {

        $user = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        Sanctum::actingAs($user);

        $note = $user->notes()->first();

        $newChanges = [
            'note'  => 'testing',
        ];

        $this->assertDatabaseHas('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);

        $this->putJson(route('notes.update', ['note' => $note->id]), $newChanges)
            ->assertJsonValidationErrors('title');

        $this->assertDatabaseMissing('notes', $newChanges);
    }

    public function test_note_validation_update_note()
    {
        $user = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        Sanctum::actingAs($user);

        $note = $user->notes()->first();

        $newChanges = [
            'title'  => 'testing',
        ];

        $this->assertDatabaseHas('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);

        $this->putJson(route('notes.update', ['note' => $note->id]), $newChanges)
            ->assertJsonValidationErrors('note');

        $this->assertDatabaseMissing('notes', $newChanges);
    }

    /**
     * Test deleting a note
     *
     * @return void
     */
    public function test_delete_note()
    {

        $user = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        $note = $user->notes()->first();

        Sanctum::actingAs($user);

        $this->assertDatabaseHas('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);

        $this->deleteJson(route('notes.destroy', ['note' => $note->id]))
            ->assertExactJson(['message' => 'note has been deleted'])
            ->assertSuccessful();

        $this->assertDatabaseMissing('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);
    }

    /**
     * Test listing notes without being loged in
     *
     * @return void
     */

    public function test_unauth_delete_note()
    {
        $this->getJson(route('notes.destroy', 1))
            ->assertUnauthorized();
    }

      /**
     * Test can't delete another user note
     *
     * @return void
     */

    public function test_unauth_delete_someone_else_note()
    {

        $user = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        $user2 = User::factory()
            ->has(Note::factory()->count(1))
            ->create();

        $note = $user->notes()->first();


        //sign in as user 2
        Sanctum::actingAs($user2);

        //check that the record is there
        $this->assertDatabaseHas('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);

        $this->putJson(route('notes.destroy', ['note' => $note->id]))
            ->assertStatus(403);

        //check record hasn't changed
        $this->assertDatabaseHas('notes', [
            'title' => $note->title,
            'note'  => $note->note
        ]);
    }


}
