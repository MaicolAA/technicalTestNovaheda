<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use Illuminate\Http\Response;


use PHPUnit\Framework\Attributes\Test;


class CompanyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->createUser());
    }

    /**
     * Testea la lista de compañias
     */
    #[Test]
    public function it_can_list_all_companies()
    {
        Company::factory()->count(2)->create();

        $response = $this->getJson('/api/companies');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'body' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'address',
                        'website',
                        'description'
                    ]
                ]
            ])
            ->assertJsonCount(2, 'body');
    }

    /**
     * Testea la creación de una compañia
     */
    #[Test]
    public function it_can_create_a_company()
    {
        $data = [
            'name' => 'Test Company',
            'description' => 'Test Description',
            'email' => 'test@example.com',
            'address' => '123 Test St',
            'website' => 'https://test.com'
        ];

        $response = $this->postJson('/api/companies', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'message' => 'Compañia creada correctamente',
                'body' => [
                    'name' => 'Test Company',
                    'email' => 'test@example.com'
                ]
            ]);

        $this->assertDatabaseHas('companies', ['email' => 'test@example.com']);
    }
    /**
     * Testea los campos requeridos al crear una compañia
     */
    #[Test]
    public function it_validates_required_fields_when_creating_company()
    {
        $response = $this->postJson('/api/companies', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'error' => [
                    "El campo nombre es requerido",
                    "El campo email es requerido"
                ]
            ]);
    }
    /**
     * Testea el la creación con email invalido
     */
    #[Test]
    public function it_validates_email_format_when_creating_company()
    {
        $response = $this->postJson('/api/companies', [
            'name' => 'Test',
            'email' => 'invalid-email'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'error' => [
                    "El campo email debe ser un correo electrónico válido"
                ]
            ]);
    }

    /**
     * Testea el ver de una compañia
     */
    #[Test]
    public function it_can_show_a_company()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("/api/companies/{$company->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'La información se cargó con éxito',
                'body' => [
                    'id' => $company->id,
                    'name' => $company->name
                ]
            ]);
    }

    /**
     * Testea el ver con compañia inexistente
     */
    #[Test]
    public function it_returns_404_when_company_not_found()
    {
        $response = $this->getJson('/api/companies/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'Compañia no encontrada'
            ]);
    }

    /**
     * Testea el actualizar de compañias
     */
    #[Test]
    public function it_can_update_a_company()
    {
        $company = Company::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'address' => 'Updated Address',
            'website' => 'https://updated.com',
            'description' => 'Updated Description'
        ];

        $response = $this->putJson("/api/companies/{$company->id}", $updateData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Compañia actualizada correctamente',
                'body' => [
                    'id' => $company->id,
                    'name' => 'Updated Name',
                    'email' => 'updated@example.com'
                ]
            ]);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Name'
        ]);
    }

    /**
     * Testea validaciones al actualizar una compañia
     */
    #[Test]
    public function it_validates_data_when_updating_company()
    {
        $company = Company::factory()->create([
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'address' => 'Short address',
            'website' => 'https://example.com',
            'description' => 'Short description'
        ]);

        $response = $this->putJson("/api/companies/{$company->id}", [
            'email' => 'invalid-email'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'status' => 422,
                'error' => [
                    "El campo email debe ser un correo electrónico válido"
                ]
            ]);
    }

    /**
     * Testea actualización a compañia inexistente
     */
    #[Test]
    public function it_returns_404_when_updating_nonexistent_company()
    {
        $response = $this->putJson('/api/companies/999', [
            'name' => 'Test',
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'Compañia no encontrada'
            ]);
    }

    /**
     * Testea eliminación de compañia
     */
    #[Test]
    public function it_can_delete_a_company()
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Compañia eliminada correctamente'
            ]);

        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    /**
     * Testea la eliminación de compañia inexistente
     */
    #[Test]
    public function it_returns_404_when_deleting_nonexistent_company()
    {
        $response = $this->deleteJson('/api/companies/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'Compañia no encontrada'
            ]);
    }

}
