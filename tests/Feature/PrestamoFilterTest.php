<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Lector;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestamoFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_loans_by_status(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $lector = Lector::create([
            'nombre' => 'Lector',
            'apellido' => 'Prueba',
            'email' => 'lector@example.com',
        ]);
        $categoria = Categoria::create(['nombre' => 'Prueba']);
        $solicitado = Libro::create([
            'titulo' => 'Libro solicitado',
            'isbn' => '9780000000002',
            'stock' => 1,
            'categoria_id' => $categoria->id,
        ]);
        $prestado = Libro::create([
            'titulo' => 'Libro prestado',
            'isbn' => '9780000000003',
            'stock' => 1,
            'categoria_id' => $categoria->id,
        ]);

        foreach ([
            [$solicitado, 'Solicitado'],
            [$prestado, 'Prestado'],
        ] as [$libro, $estado]) {
            Prestamo::create([
                'libro_id' => $libro->id,
                'lector_id' => $lector->id,
                'fecha_prestamo' => now()->toDateString(),
                'fecha_limite' => now()->addWeek()->toDateString(),
                'estado' => $estado,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('prestamos.index', [
            'estado' => 'Solicitado',
        ]));

        $response->assertOk()
            ->assertViewHas('estado', 'Solicitado')
            ->assertSee('Libro solicitado')
            ->assertDontSee('Libro prestado');
    }
}
