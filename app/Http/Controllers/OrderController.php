<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(): JsonResponse
    {
        // Obtener todos los pedidos con sus relaciones
        $orders = Order::with(['customer.user', 'delivery.deliveryPerson.user'])->get();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Pedidos obtenidos correctamente'
        ], 200);
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request): JsonResponse
    {
        // Crear nuevo pedido (simple)
        $order = Order::create([
            'id_cliente' => $request->customer_id,
            'fecha_pedido' => $request->order_date,
            'estado' => $request->status,
            'total' => $request->total
        ]);

        $order->load(['customer.user', 'delivery']);

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Pedido creado correctamente'
        ], 201);
    }

    /**
     * Display the specified order
     */
    public function show(Order $order): JsonResponse
    {
        // Cargar relaciones básicas
        $order->load(['customer.user', 'delivery.deliveryPerson.user']);

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Pedido obtenido correctamente'
        ], 200);
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        // Actualizar pedido
        $updateData = [];
        if ($request->has('status')) $updateData['estado'] = $request->status;
        if ($request->has('total')) $updateData['total'] = $request->total;

        $order->update($updateData);
        $order->load(['customer.user', 'delivery.deliveryPerson.user']);

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Pedido actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified order
     */
    public function destroy(Order $order): JsonResponse
    {
        // Eliminar pedido
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pedido eliminado correctamente'
        ], 200);
    }

    // Métodos especiales se omiten por ahora para mantenerlo simple
}
