<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    //

    use HasFactory;

    protected $fillable = [
        'nombre_cliente',
        'direccion',
        'telefono',
        'm_pago',
        'total',
    ];

    protected $casts = [
        'total' => 'decimal:2', // formato decimal
    ];


    public function pedidoProductos(): HasMany {
        return $this->hasMany(PedidoProducto::class);
    }
}
