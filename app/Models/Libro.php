<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = ['titulo', 'autor_id', 'isbn', 'stock', 'categoria_id'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'autor_id');
    }
    
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    // Un libro puede tener muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class);
    }

    // Un método extra para calcular el promedio fácilmente
    public function promedioCalificacion()
    {
        return $this->resenas()->avg('calificacion') ?: 0;
    }
}