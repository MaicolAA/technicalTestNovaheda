<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class AuthTest extends TestCase
{
    use DatabaseTransactions;
    
    /** 
     * Valida registo invalido de usuario
     */
    #[Test]
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 422,
                'message' => 'Error al registrar el usuario',
                'error' => [
                    "El campo nombre es requerido",
                    "El campo email es requerido",
                    "El campo password es requerido"
                ]
            ]);
    }

    /** 
     * Valida registro de usuario
     */
    #[Test]
    public function it_can_register_a_new_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Maicol Arroyave',
            'email' => 'maicolaroyave11110@gmail.com',
            'password' => 'gatows'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'body' => ['token']
            ])
            ->assertJson([
                'message' => 'Usuario registrado correctamente'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'maicolaroyave11110@gmail.com',
            'name' => 'Maicol Arroyave'
        ]);

        $user = User::where('email', 'maicolaroyave11110@gmail.com')->first();
        $this->assertNotEquals('gatows', $user->password);
    }

    /**
     * Valida email unico para usuarios
     */
    #[Test]
    public function it_validates_unique_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'existing@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 422,
                'message' => 'Error al registrar el usuario',
                'error' => [
                    "El email ya está en uso"
                ]
            ]);
    }

    /** 
     * Valida login exitoso
     */
    #[Test]
    public function it_can_login_with_jwt()
    {
        $email = 'test' . rand(1, 1000) . '@example.com';
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'body' => ['token']
            ])
            ->assertJson([
                'message' => 'Usuario logueado correctamente'
            ]);

        $token = $response->json('body.token');
        $authenticatedUser = JWTAuth::setToken($token)->authenticate();
        $this->assertEquals($email, $authenticatedUser->email);
    }

    /** 
     * Valida acceso a a api/me que requiere token
     */
    #[Test]
    public function it_can_access_protected_route_with_jwt()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/me', [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200);
    }

    /** 
     * Valida acceso no valido a a api/me que requiere token
     */
    #[Test]
    public function it_fails_with_invalid_jwt()
    {
        $response = $this->getJson('/api/me', [
            'Authorization' => 'Bearer invalid-token',
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Token inválido o expirado']);
    }
}
