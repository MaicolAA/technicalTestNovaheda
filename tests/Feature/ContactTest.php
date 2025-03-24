<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use App\Models\Company;
use Illuminate\Http\Response;

use PHPUnit\Framework\Attributes\Test;

class ContactTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->createUser());
    }

    #[Test]
    public function it_can_list_all_contacts()
    {
        Contact::factory()->count(3)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'body' => [
                    '*' => ['id', 'name', 'email', 'phone', 'address']
                ]
            ])
            ->assertJsonCount(3, 'body');
    }

    #[Test]
    public function it_can_create_a_contact()
    {
        $company = Company::factory()->create();

        $data = [
            'name' => 'Maicol Arroyave',
            'email' => 'maicolaroyave121@gmail.com',
            'phone' => '3128797122',
            'address' => '1000 south lane 12',
            'company_id' => $company->id
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'message' => 'Contacto creado correctamente.',
                'body' => [
                    'name' => 'Maicol Arroyave',
                    'email' => 'maicolaroyave121@gmail.com',
                    'phone' => '3128797122',
                    'address' => '1000 south lane 12',
                    'company' => [
                        'id' => $company->id
                    ]
                ]
            ]);

        $this->assertDatabaseHas('contacts', ['email' => 'maicolaroyave121@gmail.com']);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_contact()
    {
        $response = $this->postJson('/api/contacts', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'message' => 'Error al crear el contacto.',
                'error' => [
                    "El nombre del contacto es obligatorio.",
                    "El correo electrónico del contacto es obligatorio.",
                    "El ID de la compañía es obligatorio."
                ]
            ]);
    }

    #[Test]
    public function it_validates_company_exists_when_creating_contact()
    {
        $data = [
            'name' => 'Test',
            'email' => 'test@example.com',
            'company_id' => 999
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'message' => 'Error al crear el contacto.',
                'error' => [
                    "La compañía seleccionada no existe."
                ]
            ]);
    }

    #[Test]
    public function it_validates_email_format_when_creating_contact()
    {
        $company = Company::factory()->create();

        $response = $this->postJson('/api/contacts', [
            'name' => 'Test',
            'email' => 'invalid-email',
            'company_id' => $company->id
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'message' => 'Error al crear el contacto.',
                'error' => [
                    "El correo electrónico del contacto debe ser una dirección válida."
                ]
            ]);
    }

    /**
     * Valida que se muestre el detalle de un contacto
     */
    #[Test]
    public function it_can_show_a_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'La información se cargó con éxito',
                'body' => [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email
                ]
            ]);
    }

    #[Test]
    public function it_returns_404_when_contact_not_found()
    {
        $response = $this->getJson('/api/contacts/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'status' => 404,
                'message' => 'Contacto no encontrado.'
            ]);
    }

    #[Test]
    public function it_can_update_a_contact()
    {
        $contact = Contact::factory()->create();
        $company = Company::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '1234567890',
            'address' => 'Updated Address',
            'company_id' => $company->id
        ];

        $response = $this->putJson("/api/contacts/{$contact->id}", $updateData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Contacto actualizado correctamente.',
                'body' => [
                    'name' => 'Updated Name',
                    'email' => 'updated@example.com',
                    'company' => [
                        'id' => $company->id
                    ]
                ]
            ]);
    }

    #[Test]
    public function it_validates_data_when_updating_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->putJson("/api/contacts/{$contact->id}", [
            'email' => 'invalid-email'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'message' => 'Error al actualizar el contacto.',
                'error' => [
                    "El correo electrónico del contacto debe ser una dirección válida."
                ]
            ]);
    }

    #[Test]
    public function it_returns_404_when_updating_nonexistent_contact()
    {
        $response = $this->putJson('/api/contacts/999', [
            'name' => 'Test'
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'status' => 404,
                'message' => 'Contacto no encontrado.'
            ]);
    }

    #[Test]
    public function it_can_delete_a_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'status' => 200,
                'message' => 'Contacto eliminado correctamente.'
            ]);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_contact()
    {
        $response = $this->deleteJson('/api/contacts/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'status' => 404,
                'message' => 'Contacto no encontrado.'
            ]);
    }
}
