<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Factories\HasFactory;
class Producto extends Model
{

    use HasFactory;
    
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
