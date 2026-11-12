<?php

    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;

    class Libro extends Model
    {
        protected $fillable = ['titulo', 'autor', 'isbn', 'stock', 'categoria_id'];

        public function categoria()
        {
            return $this->belongsTo(Categoria::class);
        }

        public function prestamos()
        {
            return $this->hasMany(Prestamo::class);
        }
    }
