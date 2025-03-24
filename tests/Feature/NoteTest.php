<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Note;
use App\Models\Contact;
use App\Models\Company;
use Illuminate\Http\Response;

use PHPUnit\Framework\Attributes\Test;

class NoteTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->createUser());
    }


    #[Test]
    public function it_can_create_a_note_for_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->postJson('/api/notes', [
            'note' => 'Nota para contacto',
            'noteable_type' => 'contact',
            'noteable_id' => $contact->id
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'message' => 'Nota creada correctamente.',
                'body' => [
                    'noteable_type' => 'contact',
                    'noteable_id' => $contact->id
                ]
            ]);

        $this->assertDatabaseHas('notes', [
            'noteable_type' => Contact::class,
            'noteable_id' => $contact->id
        ]);
    }

    #[Test]
    public function it_can_create_a_note_for_company()
    {
        $company = Company::factory()->create();

        $response = $this->postJson('/api/notes', [
            'note' => 'Nota para compañía',
            'noteable_type' => 'company',
            'noteable_id' => $company->id
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'message' => 'Nota creada correctamente.',
                'body' => [
                    'noteable_type' => 'company',
                    'noteable_id' => $company->id
                ]
            ]);
    }

    #[Test]
    public function it_validates_noteable_type_values()
    {
        $response = $this->postJson('/api/notes', [
            'note' => 'Test',
            'noteable_type' => 'invalid_type',
            'noteable_id' => 1
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'message' => 'Error al crear la nota.',
                'error' => [
                    "El tipo de entidad (noteable_type) no es válido. Los valores permitidos son: company, contact."
                ]
            ]);
    }


    #[Test]
    public function it_lists_notes()
    {
        Note::factory()->count(3)->create();

        $response = $this->getJson('/api/notes/');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'body' => [
                    '*' => ['id', 'note', 'noteable_type', 'noteable_id']
                ]
            ]);
    }

    #[Test]
    public function it_filters_notes_by_noteable_type()
    {
        Note::query()->delete();

        Note::factory()->count(2)->create(['noteable_type' => Contact::class]);
        Note::factory()->create(['noteable_type' => Company::class]);

        $response = $this->getJson('/api/notes?noteable_type=contact');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'body')
            ->assertJson([
                'body' => [
                    ['noteable_type' => 'contact'],
                    ['noteable_type' => 'contact']
                ]
            ]);
    }

    #[Test]
    public function it_returns_notes_for_specific_contact()
    {
        $contact = Contact::factory()->create();
        $notes = Note::factory()->count(3)->create([
            'noteable_type' => Contact::class,
            'noteable_id' => $contact->id
        ]);

        Note::factory()->count(2)->create([
            'noteable_type' => Contact::class,
            'noteable_id' => Contact::factory()->create()->id
        ]);

        $response = $this->getJson("/api/notes?noteable_type=contact&noteable_id={$contact->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'body');
    }

    #[Test]
    public function it_returns_notes_for_specific_company()
    {
        $company = Company::factory()->create();
        $notes = Note::factory()->count(3)->create([
            'noteable_type' => Company::class,
            'noteable_id' => $company->id
        ]);

        Note::factory()->count(2)->create([
            'noteable_type' => Company::class,
            'noteable_id' => Company::factory()->create()->id
        ]);

        $response = $this->getJson("/api/notes?noteable_type=company&noteable_id={$company->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'body');
    }

    #[Test]
    public function it_validates_required_fields_when_creating_note()
    {
        $response = $this->postJson('/api/notes', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'message' => 'Error al crear la nota.',
                'error' => [
                    "El contenido de la nota es obligatorio.",
                    "El tipo de entidad (noteable_type) es obligatorio.",
                    "El ID de la entidad (noteable_id) es obligatorio."
                ]
            ]);
    }

    #[Test]
    public function it_returns_404_when_noteable_not_found()
    {
        $response = $this->postJson('/api/notes', [
            'note' => 'Test',
            'noteable_type' => 'contact',
            'noteable_id' => 999
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'status' => 400,
                'message' => 'Contacto no encontrado.'
            ]);
    }

    #[Test]
    public function it_shows_note()
    {
        $contact = Contact::factory()->create();
        $note = Note::factory()->create([
            'noteable_type' => Contact::class,
            'noteable_id' => $contact->id
        ]);

        $response = $this->getJson("/api/notes/{$note->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'La información se cargó con éxito',
                'body' => [
                    'id' => $note->id,
                    'noteable_type' => 'contact',
                    'noteable_id' => $contact->id
                ]
            ]);
    }

    #[Test]
    public function it_returns_404_when_note_not_found()
    {
        $response = $this->getJson("/api/notes/999");

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'status' => 404,
                'message' => 'La nota no fue encontrada.'
            ]);
    }

    #[Test]
    public function it_can_update_note()
    {
        $contact = Contact::factory()->create();
        $company = Company::factory()->create();
        $note = Note::factory()->create([
            'noteable_type' => Contact::class,
            'noteable_id' => $contact->id
        ]);

        $response = $this->putJson("/api/notes/{$note->id}", [
            'note' => 'Nota actualizada',
            'noteable_type' => 'company',
            'noteable_id' => $company->id
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Nota actualizada correctamente.',
                'body' => [
                    'noteable_type' => 'company',
                    'noteable_id' => $company->id
                ]
            ]);
    }

    #[Test]
    public function it_validates_required_fields_when_updating_note()
    {
        $note = Note::factory()->create();

        $response = $this->putJson("/api/notes/{$note->id}", []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'message' => 'Error actualizando la nota.',
                'error' => [
                    "El contenido de la nota es obligatorio.",
                    "El tipo de entidad (noteable_type) es obligatorio.",
                    "El ID de la entidad (noteable_id) es obligatorio."
                ]
            ]);
    }

    #[Test]
    public function it_returns_404_when_updating_nonexistent_note()
    {
        $response = $this->putJson("/api/notes/999", [
            'note' => 'Test',
            'noteable_type' => 'contact',
            'noteable_id' => 1
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'status' => 404,
                'message' => 'La nota no fue encontrada.'
            ]);
    }

    #[Test]
    public function it_returns_404_when_updating_with_nonexistent_noteable()
    {
        $note = Note::factory()->create();

        $response = $this->putJson("/api/notes/{$note->id}", [
            'note' => 'Test',
            'noteable_type' => 'company',
            'noteable_id' => 999
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'status' => 404,
                'message' => 'Compañia no encontrada'
            ]);
    }

    #[Test]
    public function it_can_delete_note()
    {
        $note = Note::factory()->create();

        $response = $this->deleteJson("/api/notes/{$note->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'status' => 200,
                'message' => 'Nota eliminada correctamente.'
            ]);

        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_note()
    {
        $response = $this->deleteJson("/api/notes/999");

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'status' => 404,
                'message' => 'La nota no fue encontrada.'
            ]);
    }

}
