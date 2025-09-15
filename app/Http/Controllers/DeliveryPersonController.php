<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPerson;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DeliveryPersonController extends Controller
{
    /**
     * Display a listing of delivery people
     */
    public function index(): JsonResponse
    {
        // Obtener todos los domiciliarios con relaciones
        $deliveryPeople = DeliveryPerson::with(['user', 'vehicles', 'deliveries.order.customer.user'])->get();

        // Respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $deliveryPeople,
            'message' => 'Domiciliarios obtenidos correctamente'
        ], 200);
    }

    /**
     * Store a newly created delivery person
     */
    public function store(Request $request): JsonResponse
    {
        // Crear nuevo domiciliario (simple)
        $deliveryPerson = DeliveryPerson::create([
            'id_usuario' => $request->user_id,
            'estado_dis' => $request->availability_status ?? false
        ]);

        $deliveryPerson->load(['user', 'vehicles', 'deliveries']);

        return response()->json([
            'success' => true,
            'data' => $deliveryPerson,
            'message' => 'Domiciliario creado correctamente'
        ], 201);
    }

    /**
     * Display the specified delivery person
     */
    public function show(DeliveryPerson $deliveryPerson): JsonResponse
    {
        // Cargar relaciones
        $deliveryPerson->load(['user', 'vehicles', 'deliveries.order.customer.user']);

        return response()->json([
            'success' => true,
            'data' => $deliveryPerson,
            'message' => 'Domiciliario obtenido correctamente'
        ], 200);
    }

    /**
     * Update the specified delivery person
     */
    public function update(Request $request, DeliveryPerson $deliveryPerson): JsonResponse
    {
        // Actualizar domiciliario
        $deliveryPerson->update([
            'estado_dis' => $request->availability_status ?? $deliveryPerson->estado_dis
        ]);

        $deliveryPerson->load(['user', 'vehicles', 'deliveries']);

        return response()->json([
            'success' => true,
            'data' => $deliveryPerson,
            'message' => 'Domiciliario actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified delivery person
     */
    public function destroy(DeliveryPerson $deliveryPerson): JsonResponse
    {
        // Eliminar domiciliario
        $deliveryPerson->delete();

        return response()->json([
            'success' => true,
            'message' => 'Domiciliario eliminado correctamente'
        ], 200);
    }

    /**
     * Get available delivery people
     */
    public function getAvailable(): JsonResponse
    {
        // Buscar solo los domiciliarios disponibles
        $availableDeliveryPeople = DeliveryPerson::where('estado_dis', true)
            ->with(['user', 'vehicles'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availableDeliveryPeople,
            'message' => 'Domiciliarios disponibles obtenidos correctamente'
        ], 200);
    }

    /**
     * Update availability status
     */
    public function updateAvailability(Request $request, DeliveryPerson $deliveryPerson): JsonResponse
    {
        // Actualizar estado de disponibilidad
        $deliveryPerson->update([
            'estado_dis' => $request->availability_status
        ]);

        return response()->json([
            'success' => true,
            'data' => $deliveryPerson,
            'message' => 'Disponibilidad actualizada correctamente'
        ], 200);
    }

    /**
     * Get delivery person by user ID
     */
    public function getByUserId(Request $request): JsonResponse
    {
        // Buscar domiciliario por id de usuario
        $deliveryPerson = DeliveryPerson::where('id_usuario', $request->user_id)
            ->with(['user', 'vehicles', 'deliveries.order.customer.user'])
            ->first();

        return response()->json([
            'success' => true,
            'data' => $deliveryPerson,
            'message' => 'Domiciliario obtenido correctamente'
        ], 200);
    }

    /**
     * Get delivery person statistics
     */
    public function getStatistics(DeliveryPerson $deliveryPerson): JsonResponse
    {
        // Estadísticas simples del domiciliario
        $totalDeliveries = $deliveryPerson->deliveries()->count();
        $completedDeliveries = $deliveryPerson->deliveries()->where('estado', true)->count();
        $pendingDeliveries = $deliveryPerson->deliveries()->where('estado', false)->count();

        $statistics = [
            'total_deliveries' => $totalDeliveries,
            'completed_deliveries' => $completedDeliveries,
            'pending_deliveries' => $pendingDeliveries,
            'completion_rate' => $totalDeliveries > 0 ? round(($completedDeliveries / $totalDeliveries) * 100, 2) : 0
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
            'message' => 'Estadísticas obtenidas correctamente'
        ], 200);
    }
}
