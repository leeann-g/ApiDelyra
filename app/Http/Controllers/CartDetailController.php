<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartDetailController extends Controller
{
    /**
     * Listar detalles de carritos
     */
    public function index()
    {
        // Obtener todos los detalles de carritos
        $details = DB::table('cart_details')->get();

        return response()->json([
            'success' => true,
            'data' => $details,
            'message' => 'Cart details retrieved successfully'
        ], 200);
    }

    /**
     * Crear nuevo detalle de carrito
     */
    public function store(Request $request)
    {
        DB::table('cart_details')->insert([
            'id_carrito' => $request->id_carrito,
            'id_producto' => $request->id_producto,
            'precio_unitario' => $request->precio_unitario,
            'cantidad' => $request->cantidad,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cart detail created successfully'
        ], 201);
    }

    /**
     * Mostrar un detalle específico (claves compuestas)
     */
    public function show($id_carrito, $id_producto)
    {
        $detail = DB::table('cart_details')
            ->where('id_carrito', $id_carrito)
            ->where('id_producto', $id_producto)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $detail,
            'message' => 'Cart detail retrieved successfully'
        ], 200);
    }

    /**
     * Actualizar un detalle específico
     */
    public function update(Request $request, $id_carrito, $id_producto)
    {
        DB::table('cart_details')
            ->where('id_carrito', $id_carrito)
            ->where('id_producto', $id_producto)
            ->update([
                'precio_unitario' => $request->precio_unitario,
                'cantidad' => $request->cantidad,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Cart detail updated successfully'
        ], 200);
    }

    /**
     * Eliminar un detalle específico
     */
    public function destroy($id_carrito, $id_producto)
    {
        DB::table('cart_details')
            ->where('id_carrito', $id_carrito)
            ->where('id_producto', $id_producto)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart detail deleted successfully'
        ], 200);
    }
}


