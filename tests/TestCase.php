<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\App;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    protected $authenticatedUser;
    protected $jwtToken;

    protected function setUp(): void
    {
        parent::setUp();
        App::setLocale('es');

        $this->configureInMemoryDatabase();
        $this->runMigrationsAndSeeders();

        $this->authenticatedUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password123')
            ]
        );

       
        $token = JWTAuth::fromUser($this->authenticatedUser);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);
    }


    protected function configureInMemoryDatabase(): void
    {
        if (config('database.default') === 'sqlite') {
            $databasePath = database_path('database.sqlite');

            if (!file_exists($databasePath)) {
                touch($databasePath);
            }

            DB::connection()->getPdo()->exec("PRAGMA foreign_keys = ON");
        }
    }

    protected function runMigrationsAndSeeders(): void
    {
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
    }

    protected function authenticate($user = null): string
    {
        $user = $user ?? $this->authenticatedUser;
        $this->jwtToken = JWTAuth::fromUser($user);
        return $this->jwtToken;
    }

    protected function authHeaders($user = null): array
    {
        $token = $this->authenticate($user);

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Invalidar token
     */
    protected function logout(): void
    {
        JWTAuth::invalidate($this->jwtToken);
        $this->jwtToken = null;
    }

    /**
     * Método helper para crear datos de prueba
     */
    protected function createTestData(string $model, array $data = [], int $count = 1)
    {
        $factory = $model::factory();

        return $count === 1
            ? $factory->create($data)
            : $factory->count($count)->create($data);
    }

    /**
     * Verificar estructura JSON básica de respuesta
     */
    protected function assertJsonStructure($response, bool $hasBody = true): void
    {
        $response->assertJsonStructure([
            'message',
            ...($hasBody ? ['body'] : [])
        ]);
    }

    /**
     * Verificar respuesta de error
     */
    protected function assertErrorResponse($response, int $statusCode, string $message = null): void
    {
        $response->assertStatus($statusCode);

        if ($message) {
            $response->assertJson(['message' => $message]);
        }

        $this->assertJsonStructure($response, false);
    }

    /**
     * Verificar respuesta de éxtio
     */
    protected function assertSuccessResponse($response, int $statusCode, string $message = null): void
    {
        $response->assertStatus($statusCode);

        if ($message) {
            $response->assertJson(['message' => $message]);
        }

        $this->assertJsonStructure($response);
    }

    /**
     * Obtener el usuario autenticado JWT
     */
    protected function getAuthenticatedUser()
    {
        return JWTAuth::setToken($this->jwtToken)->authenticate();
    }

    protected function createUser()
    {
        return \App\Models\User::factory()->create();
    }
}
