<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
    {
        $productos = [
            [
                'nombre' => 'Mouse',
                'descripcion' => 'Mouse de color rojo',
                'precio' => 75.00,
                'imagen_url' => 'images/1750836669.jpg',
                'stock' => 90,
            ],
            [
                'nombre' => 'Pachón Stanley',
                'descripcion' => 'Pachón Stanley de 40 onzas',
                'precio' => 150.00,
                'imagen_url' => 'images/1750836652.jpg',
                'stock' => 95,
            ],
            [
                'nombre' => 'Pachón Hydroflask',
                'descripcion' => 'Pachón Hydroflask de 20 onzas',
                'precio' => 175.00,
                'imagen_url' => 'images/1750836634.jpg',
                'stock' => 100,
            ],
            [
                'nombre' => 'Mouse Razer',
                'descripcion' => 'Mouse Razer inalámbrico',
                'precio' => 85.75,
                'imagen_url' => 'images/1750836610.jpg',
                'stock' => 100,
            ],
            [
                'nombre' => 'Teclado Redragon',
                'descripcion' => 'Teclado mecánico rosado',
                'precio' => 125.00,
                'imagen_url' => 'images/1750836596.jpg',
                'stock' => 50,
            ],
            [
                'nombre' => 'Alexa',
                'descripcion' => 'Alexa color negro',
                'precio' => 250.00,
                'imagen_url' => 'images/1750836564.jpg',
                'stock' => 50,
            ],
            [
                'nombre' => 'IPhone 16 Pro Max',
                'descripcion' => 'IPhone 16 Pro Max de 256 GB',
                'precio' => 10000.00,
                'imagen_url' => 'images/1750836544.jpg',
                'stock' => 50,
            ],
            [
                'nombre' => 'Samsung Galaxy Tab S9 FE+',
                'descripcion' => 'Samsung Galaxy Tab S9 FE+',
                'precio' => 7000.00,
                'imagen_url' => 'images/1750836510.jpg',
                'stock' => 95,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
