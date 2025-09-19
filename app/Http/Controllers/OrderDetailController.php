<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDetailController extends Controller
{
    /**
     * Listar detalles de pedidos
     */
    public function index()
    {
        // Obtener todos los detalles de pedidos
        $details = DB::table('order_details')->get();

        return response()->json([
            'success' => true,
            'data' => $details,
            'message' => 'Order details retrieved successfully'
        ], 200);
    }

    /**
     * Crear nuevo detalle de pedido
     */
    public function store(Request $request)
    {
        // Insertar registro con claves compuestas
        DB::table('order_details')->insert([
            'id_pedido' => $request->id_pedido,
            'id_producto' => $request->id_producto,
            'cantidad_productos' => $request->cantidad_productos,
            'precio_unitario' => $request->precio_unitario,
            'total' => $request->total,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Devolver respuesta
        return response()->json([
            'success' => true,
            'message' => 'Order detail created successfully'
        ], 201);
    }

    /**
     * Mostrar un detalle específico (por claves compuestas)
     */
    public function show($id_pedido, $id_producto)
    {
        $detail = DB::table('order_details')
            ->where('id_pedido', $id_pedido)
            ->where('id_producto', $id_producto)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $detail,
            'message' => 'Order detail retrieved successfully'
        ], 200);
    }

    /**
     * Actualizar un detalle específico
     */
    public function update(Request $request, $id_pedido, $id_producto)
    {
        DB::table('order_details')
            ->where('id_pedido', $id_pedido)
            ->where('id_producto', $id_producto)
            ->update([
                'cantidad_productos' => $request->cantidad_productos,
                'precio_unitario' => $request->precio_unitario,
                'total' => $request->total,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Order detail updated successfully'
        ], 200);
    }

    /**
     * Eliminar un detalle específico
     */
    public function destroy($id_pedido, $id_producto)
    {
        DB::table('order_details')
            ->where('id_pedido', $id_pedido)
            ->where('id_producto', $id_producto)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order detail deleted successfully'
        ], 200);
    }
}


