<?php

    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;

    class Lector extends Model
    {
        protected $fillable = ['nombre', 'apellido', 'email', 'telefono'];

        public function prestamos()
        {
            return $this->hasMany(Prestamo::class);
        }
    }
