<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = ['libro_id', 'lector_id', 'fecha_reserva', 'estado'];

    // Una reserva pertenece a un libro
    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }

    // Una reserva pertenece a un lector
    public function lector()
    {
        return $this->belongsTo(Lector::class);
    }
}