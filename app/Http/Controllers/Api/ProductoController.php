<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{

    public function index()
    {
        $productos = Producto::all();

        // Listar todos los productos
        return response()->json([
            'productos' => $productos
        ]);
    }

    public function store(Request $request)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Error de validación',
                'errores' => $validator->errors(),
            ], 422);
        }

        // Manejar la imagen si existe
        $imagenUrl = null;
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $fileName = time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images'), $fileName);
            $imagenUrl = 'images/' . $fileName; // Ruta relativa desde public
        }

        // Crear el producto
        $producto = Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'imagen_url' => $imagenUrl,
        ]);

        return response()->json([
            'mensaje' => 'Producto creado correctamente',
            'producto' => $producto,
        ], 201);
    }


    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'mensaje' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json([
            'producto' => $producto
        ]);
    }
}
