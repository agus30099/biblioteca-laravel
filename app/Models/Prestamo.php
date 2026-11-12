<?php

    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;

    class Prestamo extends Model
    {
        protected $fillable = ['libro_id', 'lector_id', 'fecha_prestamo', 'fecha_devolucion', 'estado'];

        public function libro()
        {
            return $this->belongsTo(Libro::class);
        }

        public function lector()
        {
            return $this->belongsTo(Lector::class);
        }
    }
