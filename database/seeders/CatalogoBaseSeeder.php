<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Support\Str;

class CatalogoBaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Categorías si no existen
        $categoriasNombres = ['Ciencia Ficción', 'Programación', 'Novela', 'Fantasía', 'Historia', 'Misterio', 'Terror', 'Ciencia', 'Biografía', 'Poesía'];
        $categorias = [];
        foreach ($categoriasNombres as $nombre) {
            $categorias[$nombre] = Categoria::firstOrCreate(['nombre' => $nombre], ['descripcion' => 'Libros de ' . $nombre]);
        }

        // 2. Crear Autores
        $autoresNombres = ['Frank Herbert', 'Robert C. Martin', 'George Orwell', 'Gabriel García Márquez', 'J.K. Rowling', 'Isaac Asimov', 'J.R.R. Tolkien', 'Yuval Noah Harari', 'Agatha Christie', 'Stephen King', 'Julio Verne', 'Ray Bradbury', 'Arthur C. Clarke', 'Ernest Hemingway', 'Jane Austen'];
        $autores = [];
        foreach ($autoresNombres as $nombre) {
            $autores[$nombre] = Autor::firstOrCreate(['nombre' => $nombre], ['nacionalidad' => 'Internacional']);
        }

        // 3. Lista base de libros para generar los 80 (Título, Autor, Categoría, ISBN)
        $librosBase = [
            ['Dune', 'Frank Herbert', 'Ciencia Ficción', '978-0441172719'],
            ['Clean Code', 'Robert C. Martin', 'Programación', '978-0132350884'],
            ['1984', 'George Orwell', 'Novela', '978-0451524935'],
            ['Cien Años de Soledad', 'Gabriel García Márquez', 'Novela', '978-0307474728'],
            ['Harry Potter y la Piedra Filosofal', 'J.K. Rowling', 'Fantasía', '978-8478884452'],
            ['Yo, Robot', 'Isaac Asimov', 'Ciencia Ficción', '978-8497931625'],
            ['El Hobbit', 'J.R.R. Tolkien', 'Fantasía', '978-8445071748'],
            ['Sapiens', 'Yuval Noah Harari', 'Historia', '978-8499926223'],
            ['Asesinato en el Orient Express', 'Agatha Christie', 'Misterio', '978-8497931595'],
            ['El Resplandor', 'Stephen King', 'Terror', '978-8497594110'],
            ['Viaje al Centro de la Tierra', 'Julio Verne', 'Ciencia Ficción', '978-8497941556'],
            ['Fahrenheit 451', 'Ray Bradbury', 'Ciencia Ficción', '978-8445076660'],
            ['2001: Odisea en el Espacio', 'Arthur C. Clarke', 'Ciencia Ficción', '978-8497345391'],
            ['El Viejo y el Mar', 'Ernest Hemingway', 'Novela', '978-8437604301'],
            ['Orgullo y Prejuicio', 'Jane Austen', 'Novela', '978-8491051299'],
            ['El Señor de los Anillos', 'J.R.R. Tolkien', 'Fantasía', '978-8445071779'],
            ['Fundación', 'Isaac Asimov', 'Ciencia Ficción', '978-8497931625'],
            ['Refactoring', 'Robert C. Martin', 'Programación', '978-0134757599'],
            ['Rebelión en la Granja', 'George Orwell', 'Novela', '978-8499891467'],
            ['El Amor en los Tiempos del Cólera', 'Gabriel García Márquez', 'Novela', '978-0307387264'],
        ];

        // 4. Generar los 80 libros
        $totalLibros = 80;
        $librosCreados = 0;

        foreach ($librosBase as $index => $libro) {
            $categoria = $categorias[$libro[2]];
            $autor = $autores[$libro[1]];

            // Si ya existe el ISBN, no lo insertamos (para no duplicar)
            Libro::firstOrCreate(
                ['isbn' => str_replace('-', '', $libro[3])], 
                [
                    'titulo' => $libro[0],
                    'autor_id' => $autor->id,
                    'categoria_id' => $categoria->id,
                    'stock' => rand(1, 10),
                ]
            );
            $librosCreados++;
        }

        // Si no llegamos a 80, duplicamos los base con variaciones (Edición Especial, Volumen 2, etc.)
        while ($librosCreados < $totalLibros) {
            $libroBase = $librosBase[array_rand($librosBase)];
            $nuevoTitulo = $libroBase[0] . ' - Edición ' . rand(2, 20);
            $nuevoIsbn = $libroBase[3] . rand(10, 99); // Variación en el ISBN
            
            $categoria = $categorias[$libroBase[2]];
            $autor = $autores[$libroBase[1]];

            Libro::firstOrCreate(
                ['isbn' => str_replace('-', '', $nuevoIsbn)],
                [
                    'titulo' => $nuevoTitulo,
                    'autor_id' => $autor->id,
                    'categoria_id' => $categoria->id,
                    'stock' => rand(1, 5),
                ]
            );
            $librosCreados++;
        }

        $this->command->info('¡Se han insertado ' . $librosCreados . ' libros base en el catálogo!');
    }
}