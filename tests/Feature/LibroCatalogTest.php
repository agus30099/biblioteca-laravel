<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Lector;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibroCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_shows_an_active_loan_for_users_with_the_usuario_role(): void
    {
        $user = User::create([
            'name' => 'Lector de Prueba',
            'email' => 'lector@example.com',
            'password' => 'password',
            'role' => 'usuario',
        ]);
        $lector = Lector::create([
            'nombre' => 'Lector',
            'apellido' => 'Prueba',
            'email' => $user->email,
        ]);
        $categoria = Categoria::create(['nombre' => 'Prueba']);
        $libro = Libro::create([
            'titulo' => 'Libro de prueba',
            'isbn' => '9780000000001',
            'stock' => 1,
            'categoria_id' => $categoria->id,
        ]);
        Prestamo::create([
            'libro_id' => $libro->id,
            'lector_id' => $lector->id,
            'fecha_prestamo' => now()->toDateString(),
            'fecha_limite' => now()->addWeek()->toDateString(),
            'estado' => 'Solicitado',
        ]);

        $response = $this->actingAs($user)->get(route('libros.index'));

        $response->assertOk()
            ->assertSee('En proceso')
            ->assertDontSee('Solicitar');
    }
}
