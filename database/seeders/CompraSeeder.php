<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CompraSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 15; $i++) {
            DB::transaction(function () use ($faker) {
                $productos = Producto::inRandomOrder()->take(rand(1, 3))->get();
                $total = 0;

                // Simulamos compras en fechas variadas para probar filtros en el historial
                $fechaFalsa = $faker->dateTimeBetween('-1 month', 'now');

                $compra = Compra::create([
                    'comprador' => $faker->name,
                    'total' => 0,
                    'created_at' => $fechaFalsa,
                    'updated_at' => $fechaFalsa,
                ]);

                foreach ($productos as $producto) {
                    $cantidad = rand(1, 3);
                    $subtotal = $producto->precio * $cantidad;
                    $total += $subtotal;

                    DetalleCompra::create([
                        'compra_id' => $compra->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $producto->precio,
                        'subtotal' => $subtotal,
                        'created_at' => $fechaFalsa,
                        'updated_at' => $fechaFalsa,
                    ]);
                }

                $compra->update(['total' => $total]);
            });
        }
    }
}
