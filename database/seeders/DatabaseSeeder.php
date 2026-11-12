<?php

    namespace Database\Seeders;

    use App\Models\User;
    use App\Models\Categoria;
    use App\Models\Libro;
    use App\Models\Lector;
    use App\Models\Prestamo;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;

    class DatabaseSeeder extends Seeder
    {
        public function run(): void
        {
            // Crear un usuario administrador
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]);

            // Crear categorías
            $cat1 = Categoria::create([
                'nombre' => 'Ciencia Ficción', 'descripcion' => 'Libros del futuro']);
            $cat2 = Categoria::create([
                'nombre' => 'Programación', 'descripcion' => 'Libros de tecnologia']);

            // Crear libros
            Libro::create(['titulo' => 'Dune', 'autor' => 'Frank Herbert', 'isbn' => '11111', 'stock' => 5, 'categoria_id' => $cat1->id]);
            Libro::create(['titulo' => 'Clean Code', 'autor' => 'Robert C. Martin', 'isbn' => '22222', 'stock' => 3, 'categoria_id' => $cat2->id]);

            // 4. Crear Lectores
            $lec1 = Lector::create(['nombre' => 'Juan', 'apellido' => 'Perez', 'email' => 'juan@test.com', 'telefono' => '1234567890']);
            
            // 5. Crear un Préstamo de prueba
            Prestamo::create([
                'libro_id' => 1,
                'lector_id' => $lec1->id,
                'fecha_prestamo' => date('Y-m-d'),
                'fecha_devolucion' => null,
                'estado' => 'Prestado'
            ]);
        }
    }
