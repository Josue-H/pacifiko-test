<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $table = 'producto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'imagen_url',
        'stock',
    ];

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleCompra::class);
    }
}
