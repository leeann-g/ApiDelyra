<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\DeliveryPerson;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    /**
     * Display a listing of deliveries
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todas las entregas con sus relaciones (pedido, cliente, usuario, domiciliario)
            $deliveries = Delivery::with(['order.customer.user', 'deliveryPerson.user'])->get();

            // Devolver respuesta exitosa con los datos
            return response()->json([
                'success' => true,
                'data' => $deliveries,
                'message' => 'Deliveries retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving deliveries: ' . $e->getMessage()
            ], 500);
        }
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

        // Devolver respuesta exitosa
        return response()->json([
            'success' => true,
            'data' => $delivery,
            'message' => 'Delivery created successfully'
        ], 201);
    }

    /**
     * Display the specified delivery
     */
    public function show(Delivery $delivery): JsonResponse
    {
        try {
            $delivery->load(['order.customer.user', 'deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $delivery,
                'message' => 'Delivery retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving delivery: ' . $e->getMessage()
            ], 500);
        }
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

        // Devolver respuesta exitosa
        return response()->json([
            'success' => true,
            'data' => $delivery,
            'message' => 'Delivery updated successfully'
        ], 200);
    }

    /**
     * Remove the specified delivery
     */
    public function destroy(Delivery $delivery): JsonResponse
    {
        // Eliminar la entrega
        $delivery->delete();

        // Devolver respuesta exitosa
        return response()->json([
            'success' => true,
            'message' => 'Delivery deleted successfully'
        ], 200);
    }

}
