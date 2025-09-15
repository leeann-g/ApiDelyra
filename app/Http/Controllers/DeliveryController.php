<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DeliveryController extends Controller
{
    /**
     * Display a listing of deliveries
     */
    public function index(): JsonResponse
    {
        // Obtener todas las entregas con relaciones básicas
        $deliveries = Delivery::with(['order.customer.user', 'deliveryPerson.user'])->get();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $deliveries,
            'message' => 'Entregas obtenidas correctamente'
        ], 200);
    }

    /**
     * Store a newly created delivery
     */
    public function store(Request $request): JsonResponse
    {
        // Crear nueva entrega
        $delivery = Delivery::create([
            'id_pedido' => $request->order_id,
            'id_domiciliario' => $request->delivery_person_id,
            'direccion_envio' => $request->shipping_address,
            'estado' => $request->status ?? false
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $delivery,
            'message' => 'Entrega creada correctamente'
        ], 201);
    }

    /**
     * Display the specified delivery
     */
    public function show(Delivery $delivery): JsonResponse
    {
        // Cargar relaciones básicas
        $delivery->load(['order.customer.user', 'deliveryPerson.user']);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $delivery,
            'message' => 'Entrega obtenida correctamente'
        ], 200);
    }

    /**
     * Update the specified delivery
     */
    public function update(Request $request, Delivery $delivery): JsonResponse
    {
        // Actualizar la entrega
        $delivery->update([
            'id_domiciliario' => $request->delivery_person_id,
            'direccion_envio' => $request->shipping_address,
            'estado' => $request->status
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $delivery,
            'message' => 'Entrega actualizada correctamente'
        ], 200);
    }

    /**
     * Remove the specified delivery
     */
    public function destroy(Delivery $delivery): JsonResponse
    {
        // Eliminar la entrega
        $delivery->delete();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'message' => 'Entrega eliminada correctamente'
        ], 200);
    }

}
