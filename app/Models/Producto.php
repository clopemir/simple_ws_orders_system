<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'activo',
        'instock'
    ];

    // Opcional: Casting para el precio
    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
        'instock' =>  'boolean'
    ];
}
