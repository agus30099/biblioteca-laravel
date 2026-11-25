<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sets_the_lector_role_and_redirects_to_the_catalog(): void
    {
        $response = $this->post('/register', [
            'name' => 'Lector de Prueba',
            'email' => 'lector@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('libros.index'));
        $this->assertAuthenticated();
        $this->assertSame('lector', User::where('email', 'lector@example.com')->value('role'));
    }
}
