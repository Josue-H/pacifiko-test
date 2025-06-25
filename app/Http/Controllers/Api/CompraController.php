<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CompraController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        $productoId = $request->query('producto_id');

        $query = Compra::with(['detalles.producto']);

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        } elseif ($fechaInicio) {
            $query->whereDate('created_at', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $query->whereDate('created_at', '<=', $fechaFin);
        }

        if ($productoId) {
            $query->whereHas('detalles', function ($q) use ($productoId) {
                $q->where('producto_id', $productoId);
            });
        }

        $compras = $query->orderByDesc('created_at')->get();

        return response()->json([
            'mensaje' => 'Compras encontradas',
            'data' => $compras
        ]);
    }




    public function store(Request $request)
    {
        $rules = [
            'comprador' => 'required|string|max:255',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|integer|exists:producto,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ];

        $mensajes = [
            'comprador.required' => 'El nombre del comprador es obligatorio.',
            'productos.required' => 'Debes incluir al menos un producto en la compra.',
            'productos.*.producto_id.required' => 'Debes especificar un producto.',
            'productos.*.producto_id.integer' => 'El ID del producto debe ser un número entero válido.',
            'productos.*.producto_id.exists' => 'El producto seleccionado no existe.',
            'productos.*.cantidad.required' => 'Debes indicar la cantidad del producto.',
            'productos.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'productos.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
        ];

        $validator = Validator::make($request->all(), $rules, $mensajes);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Error al registrar la compra',
                'error' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $compra = Compra::create([
                'comprador' => $request->comprador,
                'total' => 0,
            ]);

            $total = 0;

            foreach ($request->productos as $item) {
                $producto = Producto::find($item['producto_id']);

                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}");
                }

                $subtotal = $producto->precio * $item['cantidad'];
                $total += $subtotal;

                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal,
                ]);

                // Actualizar stock
                $producto->stock -= $item['cantidad'];
                $producto->save();
            }

            $compra->update(['total' => $total]);

            DB::commit();

            return response()->json([
                'mensaje' => 'Compra registrada con éxito',
                'compra_id' => $compra->id,
                'total' => $total
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'mensaje' => 'Error al registrar la compra',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
