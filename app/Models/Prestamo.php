<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    // ✅ Agregamos 'fecha_limite' y 'multa'
    protected $fillable = ['libro_id', 'lector_id', 'fecha_prestamo', 'fecha_limite', 'fecha_devolucion', 'estado', 'multa'];

    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }

    public function lector()
    {
        return $this->belongsTo(Lector::class);
    }
}