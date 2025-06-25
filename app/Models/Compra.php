<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compra extends Model
{
    use HasFactory;
    protected $table = 'compra';

    protected $fillable = [
        'comprador',
        'total',
    ];

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleCompra::class);
    }
}
